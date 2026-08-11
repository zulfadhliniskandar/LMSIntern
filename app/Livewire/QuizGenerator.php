<?php

namespace App\Livewire;

use App\Models\LearningMaterials;
use App\Services\AIQuizGenerationService;
use Livewire\Component;
use Exception;
use Illuminate\Support\Facades\Log;

class QuizGenerator extends Component
{
    public LearningMaterials $material;
    public int $numQuestions = 5;

    public function mount(LearningMaterials $material)
    {
        $this->material = $material->load('summary');
    }

    public function generateQuiz(AIQuizGenerationService $service)
    {
        $this->validate([
            'numQuestions' => 'required|integer|min:1|max:20',
        ]);

        try {
            $quiz = $service->generateQuizFromMaterial($this->material, $this->numQuestions);
            
            session()->flash('success', "Custom {$this->numQuestions}-question AI Quiz generated successfully!");
            return redirect()->route('quizzes.show', $quiz->id);
        } catch (Exception $e) {
            Log::error('Quiz generation error on page: ' . $e->getMessage());
            session()->flash('error', 'Failed to generate quiz: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.quiz-generator');
    }
}
