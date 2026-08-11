<?php

namespace App\Livewire;

use App\Models\LearningMaterials;
use App\Services\AIQuizGenerationService;
use App\Services\TextExtractionService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;

class AIQuizGeneratorPage extends Component
{
    use WithFileUploads;

    public $file;
    public string $title = '';
    public int $numQuestions = 5;
    public ?int $selectedMaterialId = null;

    public function getStudentId(): string
    {
        return auth()->user()?->student?->student_id ?? (string) auth()->id();
    }

    public function getMaterialsProperty()
    {
        return LearningMaterials::where('student_id', $this->getStudentId())
            ->latest()
            ->get();
    }

    public function generateQuiz(AIQuizGenerationService $aiQuizService, TextExtractionService $extractionService)
    {
        $this->validate([
            'numQuestions' => 'required|integer|min:1|max:20',
        ]);

        $material = null;

        if ($this->file) {
            $this->validate([
                'title' => 'required|string|max:255',
                'file' => 'required|file|mimes:pdf,doc,docx,txt,pptx,ppt|max:20480',
            ]);

            try {
                $storedPath = $this->file->store('learning-materials', 'local');
                $extractedText = $extractionService->extractText($storedPath);
                $wordCount = $extractionService->countWords($extractedText);

                $material = LearningMaterials::create([
                    'student_id' => $this->getStudentId(),
                    'title' => $this->title,
                    'original_filename' => $this->file->getClientOriginalName(),
                    'file_type' => strtolower($this->file->getClientOriginalExtension()),
                    'original_file_path' => $storedPath,
                    'extracted_text' => $extractedText,
                    'original_word_count' => $wordCount,
                    'simplification_status' => 'completed',
                ]);
            } catch (Exception $e) {
                Log::error('Error processing document for quiz: ' . $e->getMessage());
                session()->flash('error', 'Failed to extract text from uploaded document: ' . $e->getMessage());
                return;
            }
        } elseif ($this->selectedMaterialId) {
            $material = LearningMaterials::where('student_id', $this->getStudentId())->find($this->selectedMaterialId);
            if (!$material) {
                session()->flash('error', 'Selected material not found.');
                return;
            }
        } else {
            $this->addError('file', 'Please upload a document or select an existing material.');
            return;
        }

        try {
            $quiz = $aiQuizService->generateQuizFromMaterial($material, $this->numQuestions);

            session()->flash('success', "AI Quiz with {$this->numQuestions} questions generated successfully!");
            return redirect()->route('quizzes.show', $quiz->id);
        } catch (Exception $e) {
            Log::error('Quiz generation error: ' . $e->getMessage());
            session()->flash('error', 'Failed to generate quiz: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.ai-quiz-generator-page', [
            'materials' => $this->materials,
        ]);
    }
}
