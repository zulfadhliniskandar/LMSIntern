<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\NotebooklmGeneration;
use App\Models\LearningMaterials;
use App\Services\TextExtractionService;
use App\Services\NotebookLmService;
use App\Jobs\GenerateNotebooklmAudio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class NotebooklmAudioGenerator extends Component
{
    use WithFileUploads;

    public $sourceMode = 'text'; // 'text', 'file', 'material'
    public $textInput = '';
    public $title = '';
    public $file;
    public $selectedMaterialId = null;

    // Authentication setup
    public $showAuthModal = false;
    public $storageStateJson = '';
    public $isAuthSetup = false;

    // UI state
    public $isGenerating = false;
    public $audioList = [];
    public $currentPlaying = null;
    public $hasProcessing = false;
    public $materials = [];

    protected $listeners = ['refreshAudioList' => 'loadAudioList'];

    public function mount()
    {
        $student = Auth::user()->student;

        if ($student) {
            $this->materials = LearningMaterials::where('student_id', $student->student_id)
                ->latest()
                ->get(['id', 'title', 'original_filename', 'file_type']);
        }

        $this->checkAuthStatus();
        $this->loadAudioList();
    }

    public function checkAuthStatus()
    {
        $path = storage_path('app/notebooklm_auth/storage_state.json');
        $this->isAuthSetup = file_exists($path) && filesize($path) > 0;
    }

    public function saveAuthStorageState()
    {
        $this->validate([
            'storageStateJson' => 'required|string',
        ]);

        try {
            $json = trim($this->storageStateJson);
            $decoded = json_decode($json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON format.");
            }

            // Auto-format raw cookie array into Playwright storage_state structure if needed
            if (is_array($decoded) && isset($decoded[0]['name'])) {
                $decoded = [
                    'cookies' => array_map(function ($c) {
                        return [
                            'name' => $c['name'] ?? '',
                            'value' => $c['value'] ?? '',
                            'domain' => $c['domain'] ?? '.google.com',
                            'path' => $c['path'] ?? '/',
                            'expires' => $c['expirationDate'] ?? $c['expires'] ?? -1,
                            'httpOnly' => $c['httpOnly'] ?? false,
                            'secure' => $c['secure'] ?? true,
                            'sameSite' => 'None',
                        ];
                    }, $decoded),
                    'origins' => []
                ];
                $json = json_encode($decoded, JSON_PRETTY_PRINT);
            }

            $dir = storage_path('app/notebooklm_auth');
            @mkdir($dir, 0775, true);
            file_put_contents($dir . '/storage_state.json', $json);

            // Also copy to default user profile directory so CLI commands work seamlessly
            $homeProfileDir = env('USERPROFILE') ? env('USERPROFILE') . '/.notebooklm/profiles/default' : null;
            if ($homeProfileDir) {
                @mkdir($homeProfileDir, 0775, true);
                @file_put_contents($homeProfileDir . '/storage_state.json', $json);
            }

            $this->isAuthSetup = true;
            $this->showAuthModal = false;
            $this->storageStateJson = '';
            session()->flash('auth_message', 'NotebookLM authentication saved successfully!');

        } catch (\Exception $e) {
            $this->addError('storageStateJson', 'Error saving authentication: ' . $e->getMessage());
        }
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|file|mimes:pdf,docx,txt,pptx|max:20480',
        ]);

        if (empty($this->title) && $this->file) {
            $this->title = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
        }
    }

    public function updatedSelectedMaterialId($id)
    {
        if ($id) {
            $material = LearningMaterials::find($id);
            if ($material) {
                $this->title = $material->title;
            }
        }
    }

    public function generate()
    {
        $student = Auth::user()->student;
        if (!$student) {
            session()->flash('error', 'Student profile not found.');
            return;
        }

        $this->validate([
            'title' => 'required|string|max:255',
        ]);

        $extractedText = '';
        $learningMaterialId = null;
        $textExtractor = new TextExtractionService();

        try {
            if ($this->sourceMode === 'text') {
                $this->validate(['textInput' => 'required|string|min:20|max:50000']);
                $extractedText = trim($this->textInput);

            } elseif ($this->sourceMode === 'file') {
                $this->validate(['file' => 'required|file|mimes:pdf,docx,txt,pptx|max:20480']);
                $tempPath = $this->file->store('temp_audio_extract');
                $extractedText = $textExtractor->extractText($tempPath);
                @Storage::delete($tempPath);

            } elseif ($this->sourceMode === 'material') {
                $this->validate(['selectedMaterialId' => 'required|exists:learning_materials,id']);
                $material = LearningMaterials::find($this->selectedMaterialId);
                $learningMaterialId = $material->id;
                $extractedText = $material->simplified_text ?: ($material->extracted_text ?: ($material->original_file_path ? $textExtractor->extractText($material->original_file_path) : ''));
            }

            if (empty(trim($extractedText))) {
                session()->flash('error', 'Failed to extract text from the selected source.');
                return;
            }

            $notebookGen = NotebooklmGeneration::create([
                'student_id' => $student->student_id,
                'learning_material_id' => $learningMaterialId,
                'title' => $this->title,
                'text_input' => mb_substr($extractedText, 0, 50000),
                'status' => 'pending',
            ]);

            // Dispatch background job
            GenerateNotebooklmAudio::dispatch($notebookGen);

            $this->resetInputFields();
            $this->loadAudioList();

            session()->flash('message', 'NotebookLM Audio Overview generation started! It usually takes 1-3 minutes to complete.');

        } catch (\Exception $e) {
            session()->flash('error', 'Audio Overview request failed: ' . $e->getMessage());
        }
    }

    public function loadAudioList()
    {
        $student = Auth::user()->student;
        if (!$student) {
            $this->audioList = [];
            return;
        }

        $generations = NotebooklmGeneration::where('student_id', $student->student_id)
            ->latest()
            ->get();

        $this->hasProcessing = $generations->contains(fn($item) => in_array($item->status, ['pending', 'processing']));

        $this->audioList = $generations->map(function ($audio) {
            return [
                'id' => $audio->id,
                'title' => $audio->title,
                'status' => $audio->status,
                'audioUrl' => $audio->audio_file_path ? route('notebooklm-audio.stream', ['notebooklmGen' => $audio->id]) : null,
                'downloadUrl' => $audio->audio_file_path ? route('notebooklm-audio.download', ['notebooklmGen' => $audio->id]) : null,
                'fileSize' => $audio->file_size,
                'plays' => $audio->plays,
                'notebookId' => $audio->notebook_id,
                'createdAt' => $audio->created_at->format('M d, Y'),
                'createdAtTime' => $audio->created_at->format('h:i A'),
                'error' => $audio->error_message,
            ];
        })->toArray();
    }

    public function playAudio($id)
    {
        $audio = NotebooklmGeneration::find($id);
        if ($audio && $audio->status === 'completed') {
            $audio->recordPlay();
            $this->currentPlaying = ($this->currentPlaying === $id) ? null : $id;
            $this->loadAudioList();
        }
    }

    public function deleteAudio($id)
    {
        $audio = NotebooklmGeneration::find($id);
        if ($audio) {
            if ($audio->audio_file_path && Storage::exists($audio->audio_file_path)) {
                Storage::delete($audio->audio_file_path);
            }
            $audio->delete();
            $this->loadAudioList();
            session()->flash('message', 'Audio Overview deleted.');
        }
    }

    private function resetInputFields()
    {
        $this->textInput = '';
        $this->title = '';
        $this->file = null;
        $this->selectedMaterialId = null;
    }

    public function render()
    {
        return view('livewire.notebooklm-audio-generator');
    }
}
