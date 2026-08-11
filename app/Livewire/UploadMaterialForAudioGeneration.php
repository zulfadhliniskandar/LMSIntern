<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\AudioGeneration;
use App\Models\AudioVoicePreference;
use App\Models\LearningMaterials;
use App\Services\EdgeTtsService;
use App\Services\TextExtractionService;
use App\Jobs\GenerateAudio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class UploadMaterialForAudioGeneration extends Component
{
    use WithFileUploads;

    public $uploadMode = 'new'; // 'new' or 'existing'
    public $generationMode = 'solo'; // 'solo' or 'podcast'
    public $selectedMaterialId = null;
    public $title = '';
    public $description = '';
    public $file;

    // Voice settings
    public $selectedLanguage = 'en-US';
    public $selectedGender = 'female';
    public $selectedVoice = '';
    public $selectedStyle = 'default';
    public $speechRate = 1.0;

    // UI State
    public $isProcessing = false;
    public $audioList = [];
    public $hasProcessing = false;
    public $currentPlaying = null;

    // Voice data
    public $languages = [];
    public $availableGenders = [];
    public $styles = [];

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

    public function mount()
    {
        $this->loadVoiceData();
        $this->loadUserPreferences();
        $this->loadAudioList();
    }

    public function loadVoiceData()
    {
        $ttsService = new EdgeTtsService();
        $allVoices = $ttsService->getAvailableVoices();
        $this->languages = array_keys($allVoices);
        $this->updateVoicesForLanguage();
    }

    public function loadUserPreferences()
    {
        $preferences = AudioVoicePreference::where('student_id', $this->getStudentId())->first();

        if ($preferences) {
            $this->selectedLanguage = $preferences->preferred_language;
            $this->selectedGender = $preferences->preferred_voice_gender;
            $this->selectedStyle = $preferences->preferred_style;
            $this->speechRate = $preferences->preferred_speech_rate;
        }

        $this->updateVoicesForLanguage();
    }

    public function loadAudioList()
    {
        $results = AudioGeneration::where('student_id', $this->getStudentId())
            ->latest()
            ->get();

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
                'fileSize' => $audio->file_size ? $this->formatFileSize($audio->file_size) : null,
                'createdAt' => $audio->created_at->format('M d, Y'),
                'createdAtTime' => $audio->created_at->format('h:i A'),
                'error' => $audio->error_message,
                'aiScript' => $audio->ai_script,
                'generationMode' => $audio->generation_mode ?? 'solo',
            ];
        })->toArray();
    }

    public function updateVoicesForLanguage()
    {
        $ttsService = new EdgeTtsService();
        $allVoices = $ttsService->getAvailableVoices();

        if (!isset($allVoices[$this->selectedLanguage])) {
            return;
        }

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

    public function generateAudio()
    {
        $this->isProcessing = true;
        $material = null;
        $extractedText = '';
        $audioTitle = '';

        try {
            if ($this->uploadMode === 'existing') {
                $this->validate([
                    'selectedMaterialId' => 'required|exists:learning_materials,id',
                    'selectedLanguage' => 'required|string',
                    'selectedGender' => 'required|string',
                    'selectedStyle' => 'required|string',
                ], [
                    'selectedMaterialId.required' => 'Please select a material from the list.',
                ]);

                $material = LearningMaterials::where('student_id', $this->getStudentId())
                    ->findOrFail($this->selectedMaterialId);

                $extractedText = $material->simplified_text ?: $material->extracted_text;
                $audioTitle = $material->title . ' (Audio)';

                if (empty(trim($extractedText))) {
                    throw new \Exception('The selected material has no text content.');
                }
            } else {
                $this->validate([
                    'title' => 'required|string|max:200',
                    'description' => 'nullable|string|max:500',
                    'file' => 'required|file|max:20480|mimes:pdf,doc,docx,txt,pptx,ppt',
                    'selectedLanguage' => 'required|string',
                    'selectedGender' => 'required|string',
                    'selectedStyle' => 'required|string',
                ], [
                    'file.required' => 'Please select a document file to upload.',
                    'file.max' => 'Maximum file size allowed is 20MB.',
                    'title.required' => 'Please enter a title for your audio.',
                ]);

                // 1. Store the uploaded file
                $file = $this->file;
                $ext = strtolower($file->getClientOriginalExtension());
                $filePath = $file->store('learning-materials', 'local');

                // 2. Extract text from the document
                $extractionService = new TextExtractionService();
                $extractedText = $extractionService->extractText($filePath);
                $wordCount = $extractionService->countWords($extractedText);

                if (empty(trim($extractedText))) {
                    throw new \Exception('Could not extract text from the uploaded document. Please try a different file.');
                }

                // 3. Create LearningMaterials record
                $material = LearningMaterials::create([
                    'student_id' => $this->getStudentId(),
                    'title' => $this->title,
                    'description' => $this->description ?: null,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_type' => $ext,
                    'file_size' => $file->getSize(),
                    'original_file_path' => $filePath,
                    'extracted_text' => $extractedText,
                    'original_word_count' => $wordCount,
                    'simplification_status' => 'completed',
                ]);

                $audioTitle = $this->title;
            }

            // Truncate to 50,000 chars for Edge TTS
            $textForAudio = mb_substr($extractedText, 0, 50000);

            // 4. Create AudioGeneration record
            $audio = AudioGeneration::create([
                'student_id' => $this->getStudentId(),
                'learning_material_id' => $material->id,
                'title' => $audioTitle,
                'text_input' => $textForAudio,
                'language' => $this->selectedLanguage,
                'voice_name' => $this->selectedVoice,
                'voice_gender' => $this->selectedGender,
                'style' => $this->selectedStyle,
                'speech_rate' => $this->speechRate,
                'pitch' => 0,
                'generation_mode' => $this->generationMode,
                'status' => 'pending',
            ]);

            // 5. Dispatch the TTS generation job
            GenerateAudio::dispatch($audio);

            // Reset form
            $this->reset(['title', 'description', 'file', 'selectedMaterialId']);
            $this->loadAudioList();

            session()->flash('success', 'Material processed & audio generation queued! Your audio will be ready shortly.');

        } catch (\Exception $e) {
            Log::error('Audio from document failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to process material: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
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

        $this->loadAudioList();
        session()->flash('success', 'Audio generation retrying...');
    }

    public function deleteAudio($audioId)
    {
        $audio = AudioGeneration::where('student_id', $this->getStudentId())->findOrFail($audioId);

        if ($audio->audio_file_path && file_exists(storage_path('app/' . $audio->audio_file_path))) {
            @unlink(storage_path('app/' . $audio->audio_file_path));
        }

        $audio->delete();
        $this->loadAudioList();
        session()->flash('success', 'Audio deleted successfully.');
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
        return view('livewire.upload-material-for-audio-generation', [
            'existingMaterials' => $this->existingMaterials,
        ]);
    }
}
