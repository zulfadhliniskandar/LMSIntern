<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\QuizAttempts;


class QuizTaker extends Component
{
    public Quiz $quiz;
    public int $currentQuestionIndex = 0;
    public array $userAnswers = [];
    public bool $isSubmitted = false;
    public int $score = 0;
    public int $totalQuestions = 0;

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz->load('questions.options');
        $this->totalQuestions = $quiz->questions->count();
    }

    public function selectOption($questionId, $optionId){
        $this->userAnswers[$questionId] = $optionId;
    }

    public function nextQuestion(){
        if($this->currentQuestionIndex < $this->totalQuestions - 1){
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion(){
        if($this->currentQuestionIndex > 0){
            $this->currentQuestionIndex--;
        }
    }

    public function submitQuiz(){
        $correctCount = 0;

        foreach($this->quiz->questions as $question){
            $selectedId = $this->userAnswers[$question->id] ?? null;
            $correctOption = $question->options->firstWhere('is_correct', true);

            if($selectedId && $correctOption && $selectedId == $correctOption->id){
                $correctCount++;
            }
        }
        $this->score = $correctCount;
        $this->isSubmitted = true;

        QuizAttempts::create([
            'quiz_id' => $this->quiz->id,
            'student_id' => auth()->user()?->student?->student_id ?? (string) auth()->id(),
            'score' => $this->score,
            'total_questions' => $this->totalQuestions,
            'results' => $this->userAnswers,
        ]);
    }

    public function retake()
    {
        $this->userAnswers = [];
        $this->currentQuestionIndex = 0;
        $this->isSubmitted = false;
        $this->score = 0;
    }
    
    public function render()
    {
        return view('livewire.quiz-taker', [
            'currentQuestion' => $this->quiz->questions->values()->get($this->currentQuestionIndex),
        ]);
    }
}
