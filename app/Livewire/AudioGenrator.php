<?php

namespace App\Livewire;

use App\Models\AudioGeneration;
use App\Models\AudioVoicePreference;
use App\Models\LearningMaterials;
use App\Services\EdgeTtsService;
use App\Services\TextExtractionService;
use App\Jobs\GenerateAudio;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class AudioGenrator extends Component
{
    use WithFileUploads;

    public $sourceMode = 'text'; // 'text', 'file', 'material'
    public $generationMode = 'solo'; // 'solo' or 'podcast'
    public $textInput = '';
    public $title = '';
    public $file;
    public $selectedMaterialId = null;
    public $materialId = null;

    // Voice settings
    public $selectedLanguage = 'en-US';
    public $selectedGender = 'female';
    public $selectedVoice = '';
    public $selectedStyle = 'default';
    public $speechRate = 1.0;
    public $pitch = 0;

    // UI state
    public $showAdvanced = false;
    public $isGenerating = false;
    public $audioList = [];
    public $currentPlaying = null;
    public $hasProcessing = false;

    // Voice data
    public $languages = [];
    public $voices = [];
    public $styles = [];
    public $availableGenders = [];

    public function getStudentId(): string
    {
        return Auth::user()?->student?->student_id ?? (string) Auth::id();
    }

    public function getExistingMaterialsProperty()
    {
        return LearningMaterials::where('student_id', $this->getStudentId())
            ->latest()
            ->get();
    }

    public function mount($materialId = null)
    {
        $this->materialId = $materialId ?? request()->query('material');

        if ($this->materialId) {
            $material = LearningMaterials::where('student_id', $this->getStudentId())->find($this->materialId);
            if ($material) {
                $this->sourceMode = 'material';
                $this->selectedMaterialId = $material->id;
                $this->title = $material->title . ' (Audio)';
                $this->textInput = mb_substr($material->simplified_text ?: $material->extracted_text ?: '', 0, 5000);
            }
        }

        $this->loadVoiceData();
        $this->loadUserPreferences();
        $this->loadAudioList();
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|file|max:20480|mimes:pdf,doc,docx,txt,pptx,ppt',
        ], [
            'file.max' => 'Maximum file size is 20MB.',
            'file.mimes' => 'Supported formats: PDF, DOC, DOCX, TXT, PPTX.',
        ]);

        try {
            // Save temporary file and extract text
            $storedPath = $this->file->store('audio-temp-uploads', 'local');
            $extractionService = new TextExtractionService();
            $extracted = $extractionService->extractText($storedPath);

            if (!empty(trim($extracted))) {
                $this->textInput = mb_substr($extracted, 0, 50000);
                if (empty($this->title)) {
                    $filename = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
                    $this->title = ucfirst(str_replace(['_', '-'], ' ', $filename)) . ' Audio';
                }
                $this->dispatch('notify', message: 'Text extracted from document successfully!', type: 'success');
            } else {
                $this->dispatch('notify', message: 'Could not extract text from document.', type: 'error');
            }
        } catch (\Exception $e) {
            Log::error('Extract error on file upload: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Error extracting text: ' . $e->getMessage(), type: 'error');
        }
    }

    public function updatedSelectedMaterialId()
    {
        if ($this->selectedMaterialId) {
            $mat = LearningMaterials::where('student_id', $this->getStudentId())->find($this->selectedMaterialId);
            if ($mat) {
                $this->title = $mat->title . ' (Audio)';
                $this->textInput = mb_substr($mat->simplified_text ?: $mat->extracted_text ?: '', 0, 50000);
                $this->materialId = $mat->id;
            }
        }
    }

    public function updatedSourceMode()
    {
        if ($this->sourceMode === 'text') {
            $this->file = null;
            $this->selectedMaterialId = null;
            $this->materialId = null;
        }
    }

    public function loadVoiceData()
    {
        $ttsService = new EdgeTtsService();
        $allVoices = $ttsService->getAvailableVoices();
        $this->languages = array_keys($allVoices);
        $this->updateVoicesForLanguage();
    }

    public function loadAudioList()
    {
        $query = AudioGeneration::where('student_id', $this->getStudentId());

        $results = $query->latest()->get();
        $this->hasProcessing = $results->whereIn('status', ['pending', 'processing'])->isNotEmpty();

        $this->audioList = $results->map(function ($audio) {
            return [
                'id' => $audio->id,
                'title' => $audio->title,
                'status' => $audio->status,
                'duration' => $audio->audio_duration,
                'formattedDuration' => $audio->audio_duration ? gmdate('H:i:s', $audio->audio_duration) : null,
                'voice' => $audio->voice_name,
                'style' => $audio->style,
                'plays' => $audio->plays ?? 0,
                'downloads' => $audio->downloads ?? 0,
                'fileSize' => $audio->file_size ? $this->formatFileSize($audio->file_size) : null,
                'createdAt' => $audio->created_at->format('M d, Y'),
                'createdAtTime' => $audio->created_at->format('h:i A'),
                'error' => $audio->error_message,
                'aiScript' => $audio->ai_script,
                'generationMode' => $audio->generation_mode ?? 'solo',
            ];
        })->toArray();
    }

    public function loadUserPreferences()
    {
        $preferences = AudioVoicePreference::where('student_id', $this->getStudentId())->first();

        if ($preferences) {
            $this->selectedLanguage = $preferences->preferred_language;
            $this->selectedGender = $preferences->preferred_voice_gender;
            $this->selectedStyle = $preferences->preferred_style;
            $this->speechRate = $preferences->preferred_speech_rate;
            $this->pitch = $preferences->preferred_pitch;
        }

        $this->updateVoicesForLanguage();
    }

    public function updateVoicesForLanguage()
    {
        $ttsService = new EdgeTtsService();
        $allVoices = $ttsService->getAvailableVoices();

        if (!isset($allVoices[$this->selectedLanguage])) return;

        $this->availableGenders = array_keys($allVoices[$this->selectedLanguage]);

        if (!in_array($this->selectedGender, $this->availableGenders)) {
            $this->selectedGender = $this->availableGenders[0];
        }

        $this->updateVoicesForGender();
    }

    public function updatedSelectedLanguage()
    {
        $this->updateVoicesForLanguage();
    }

    public function updatedSelectedGender()
    {
        $this->updateVoicesForGender();
    }

    public function updateVoicesForGender()
    {
        $ttsService = new EdgeTtsService();
        $voice = $ttsService->getVoice($this->selectedLanguage, $this->selectedGender);

        if (!$voice) return;

        $this->selectedVoice = $voice['name'];
        $styles = $ttsService->getStyleForVoice($this->selectedLanguage, $this->selectedGender);
        $this->styles = $styles;

        if (!isset($styles[$this->selectedStyle])) {
            $this->selectedStyle = 'default';
        }
    }

    public function savePreferences()
    {
        AudioVoicePreference::updateOrCreate(
            ['student_id' => $this->getStudentId()],
            [
                'preferred_language' => $this->selectedLanguage,
                'preferred_voice_name' => $this->selectedVoice,
                'preferred_voice_gender' => $this->selectedGender,
                'preferred_style' => $this->selectedStyle,
                'preferred_speech_rate' => $this->speechRate,
                'preferred_pitch' => $this->pitch,
            ]
        );

        $this->dispatch('notify', message: 'Voice preferences saved!', type: 'success');
    }

    public function generate()
    {
        $this->isGenerating = true;
        $textToConvert = '';
        $audioTitle = '';
        $linkedMaterialId = $this->materialId;

        try {
            if ($this->sourceMode === 'file') {
                $this->validate([
                    'title' => 'required|string|max:200',
                    'file' => 'required|file|max:20480|mimes:pdf,doc,docx,txt,pptx,ppt',
                    'selectedLanguage' => 'required|string',
                    'selectedGender' => 'required|string',
                    'selectedVoice' => 'required|string',
                    'selectedStyle' => 'required|string',
                    'speechRate' => 'required|numeric|min:0.5|max:2.0',
                ], [
                    'file.required' => 'Please select a document file to upload.',
                    'title.required' => 'Please enter an audio title.',
                ]);

                // Store document and create LearningMaterials record
                $ext = strtolower($this->file->getClientOriginalExtension());
                $storedPath = $this->file->store('learning-materials', 'local');

                $extractionService = new TextExtractionService();
                $textToConvert = $extractionService->extractText($storedPath);
                $wordCount = $extractionService->countWords($textToConvert);

                if (empty(trim($textToConvert))) {
                    throw new \Exception('Could not extract text from uploaded document.');
                }

                $mat = LearningMaterials::create([
                    'student_id' => $this->getStudentId(),
                    'title' => $this->title,
                    'original_filename' => $this->file->getClientOriginalName(),
                    'file_type' => $ext,
                    'file_size' => $this->file->getSize(),
                    'original_file_path' => $storedPath,
                    'extracted_text' => $textToConvert,
                    'original_word_count' => $wordCount,
                    'simplification_status' => 'completed',
                ]);

                $linkedMaterialId = $mat->id;
                $audioTitle = $this->title;

            } elseif ($this->sourceMode === 'material') {
                $this->validate([
                    'selectedMaterialId' => 'required|exists:learning_materials,id',
                    'selectedLanguage' => 'required|string',
                    'selectedGender' => 'required|string',
                    'selectedVoice' => 'required|string',
                    'selectedStyle' => 'required|string',
                    'speechRate' => 'required|numeric|min:0.5|max:2.0',
                ], [
                    'selectedMaterialId.required' => 'Please select a material from your library.',
                ]);

                $mat = LearningMaterials::where('student_id', $this->getStudentId())->findOrFail($this->selectedMaterialId);
                $textToConvert = $mat->simplified_text ?: $mat->extracted_text;
                $linkedMaterialId = $mat->id;
                $audioTitle = $this->title ?: ($mat->title . ' Audio');

                if (empty(trim($textToConvert))) {
                    throw new \Exception('Selected material has no text content.');
                }

            } else {
                // Text mode
                $this->validate([
                    'textInput' => 'required|string|min:10|max:50000',
                    'title' => 'required|string|max:200',
                    'selectedLanguage' => 'required|string',
                    'selectedGender' => 'required|string',
                    'selectedVoice' => 'required|string',
                    'selectedStyle' => 'required|string',
                    'speechRate' => 'required|numeric|min:0.5|max:2.0',
                    'pitch' => 'required|integer|min:-50|max:50',
                ]);

                $textToConvert = $this->textInput;
                $audioTitle = $this->title;
            }

            // Truncate to 50,000 chars max
            $finalText = mb_substr($textToConvert, 0, 50000);

            // Create AudioGeneration record
            $audio = AudioGeneration::create([
                'student_id' => $this->getStudentId(),
                'learning_material_id' => $linkedMaterialId,
                'title' => $audioTitle,
                'text_input' => $finalText,
                'language' => $this->selectedLanguage,
                'voice_name' => $this->selectedVoice,
                'voice_gender' => $this->selectedGender,
                'style' => $this->selectedStyle,
                'speech_rate' => $this->speechRate,
                'pitch' => $this->pitch,
                'generation_mode' => $this->generationMode,
                'status' => 'pending',
            ]);

            // Queue generation job
            GenerateAudio::dispatch($audio);

            $this->dispatch('notify',
                message: 'Audio generation queued! It will be ready shortly.',
                type: 'success'
            );

            $this->reset(['textInput', 'title', 'file', 'selectedMaterialId']);
            $this->loadAudioList();

        } catch (\Exception $e) {
            $this->dispatch('notify',
                message: 'Error: ' . $e->getMessage(),
                type: 'error'
            );
        } finally {
            $this->isGenerating = false;
        }
    }

    public function playAudio($audioId)
    {
        $this->currentPlaying = $this->currentPlaying === $audioId ? null : $audioId;
    }

    public function retryAudio($audioId)
    {
        $audio = AudioGeneration::where('student_id', $this->getStudentId())->findOrFail($audioId);

        if ($audio->status !== 'failed') return;

        $audio->update(['status' => 'pending', 'error_message' => null]);
        GenerateAudio::dispatch($audio);

        $this->dispatch('notify', message: 'Audio generation retrying...', type: 'success');
        $this->loadAudioList();
    }

    public function deleteAudio($audioId)
    {
        $audio = AudioGeneration::where('student_id', $this->getStudentId())->findOrFail($audioId);

        if ($audio->audio_file_path && file_exists(storage_path('app/' . $audio->audio_file_path))) {
            @unlink(storage_path('app/' . $audio->audio_file_path));
        }

        $audio->delete();
        $this->loadAudioList();

        $this->dispatch('notify', message: 'Audio deleted successfully.', type: 'success');
    }

    public function pollAudioList()
    {
        $this->loadAudioList();
    }

    private function formatFileSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        return number_format($bytes / 1024, 1) . ' KB';
    }

    public function render()
    {
        return view('livewire.audio-genrator', [
            'existingMaterials' => $this->existingMaterials,
        ]);
    }
}