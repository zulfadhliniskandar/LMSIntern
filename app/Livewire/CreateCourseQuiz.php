<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CourseQuizOptions;
use App\Models\CourseQuizQuestions;
use App\Models\CourseQuizzes;
use App\Models\Subjects;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;


class CreateCourseQuiz extends Component
{
    public Subjects $subject;
    public string $title = '';
    public string $description = '';
    public string $open_date = '';
    public string $close_date = '';
    public ?int $time_limit = null;
    public int $max_attempts = 1;
    public array $questions = [];

    public function mount(Subjects $subject)
    {
        $instructor = auth()->user()?->instructor;
        if (!$instructor) {
            abort(403);
        }
        $this->subject = $subject;
        $this->open_date = now()->format("Y-m-d\TH:i");
        $this->close_date = now()->addDays()->format("Y-m-d\TH:i");
        
        $this->addQuestion();
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'question' => "",
            'explanation' => "",
            'options' => [
                ['option_text' => "", 'is_correct' => false],
                ['option_text' => "", 'is_correct' => false],
                ['option_text' => "", 'is_correct' => false],
                ['option_text' => "", 'is_correct' => false],
            ],
        ];
    }

    public function removeQuestion(int $questionsIndex)
    {
        if (count($this->questions) <= 1) {
            Notification::make()
                ->title("Cannot remove question")
                ->body("Cannot remove last question")
                ->danger()
                ->send();
            return;
        }
        unset($this->questions[$questionsIndex]);
        $this->questions = array_values($this->questions);
    }

    public function addOption(int $questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = [
            'option_text' => "",
            'is_correct' => false,
        ];
    }

    public function removeOption(int $questionIndex, int $optionIndex)
    {
        if (count($this->questions[$questionIndex]['options']) <= 1) {
            Notification::make()
                ->title("Cannot remove option")
                ->body("Cannot remove last option")
                ->danger()
                ->send();
            return;
        }
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }

    public function setCorrectOption(int $questionIndex, int $optionIndex)
    {
        foreach ($this->questions[$questionIndex]['options'] as $index => &$option) {
            $option['is_correct'] = ($index === $optionIndex);
        }
        Notification::make()
            ->title("Correct option updated")
            ->body("Correct option updated")
            ->success()
            ->send();
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'open_date' => 'required|date',
            'close_date' => 'required|date|after:open_date',
            'time_limit' => 'nullable|integer|min:1|max:600',
            'max_attempts' => 'required|integer|min:1|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string|max:1000',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.option_text' => 'required|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required' => 'Quiz title is required.',
            'open_date.required' => 'Open date is required.',
            'close_date.required' => 'Close date is required.',
            'close_date.after' => 'Close date must be after open date.',
            'time_limit.min' => 'Time limit must be at least 1 minute.',
            'max_attempts.required' => 'Allowed attempts count is required.',
            'max_attempts.min' => 'Allowed attempts must be at least 1.',
            'questions.required' => 'At least one question is required.',
            'questions.min' => 'At least one question is required.',
            'questions.*.question.required' => 'Question text is required.',
            'questions.*.options.required' => 'Answer options are required.',
            'questions.*.options.min' => 'At least 2 answer options are required.',
            'questions.*.options.*.option_text.required' => 'Option text is required.',
        ];
    }

    public function saveQuiz(bool $publish = false)
    {
        $this->validate();
        foreach ($this->questions as $i => $q) {
            $hasCorrect = collect($q['options'])->contains('is_correct', true);
            if (!$hasCorrect) {
                Notification::make()
                    ->title("Validation error")
                    ->body("Each question must have at least one correct option")
                    ->danger()
                    ->send();
                return;
            }
        }

        DB::transaction(function () use ($publish) {
            $quiz = CourseQuizzes::create([
                'subject_id' => $this->subject->id,
                'title' => $this->title,
                'description' => $this->description ?? null,
                'open_date' => $this->open_date,
                'close_date' => $this->close_date,
                'time_limit' => $this->time_limit ? (int) $this->time_limit : null,
                'max_attempts' => $this->max_attempts ? (int) $this->max_attempts : 1,
                'total_questions' => count($this->questions),
                'isPublished' => $publish,
            ]);

            foreach ($this->questions as $q) {
                $question = CourseQuizQuestions::create([
                    'course_quiz_id' => $quiz->id,
                    'question' => $q['question'],
                    'explanation' => $q['explanation'] ?? null,
                ]);

                foreach ($q['options'] as $optionData) {
                    CourseQuizOptions::create([
                        'question_id' => $question->id,
                        'option_text' => $optionData['option_text'],
                        'is_correct' => $optionData['is_correct'],
                    ]);
                }
            }
        });

        Notification::make()
            ->title("Quiz " . ($publish ? "published" : "saved") . " successfully")
            ->body($publish ? "Quiz published successfully" : "Quiz saved successfully")
            ->success()
            ->send();

        return $this->redirect(route("instructor-course-details", $this->subject->id), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.create-course-quiz');
    }
}
