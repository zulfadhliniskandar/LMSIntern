<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Programme;
use App\Models\advising as AdvisingModel;



class Advising extends Component
{
    public $subjectForProgrammeEnrolled;
    public $advisor;
    #[Validate([
        'applySubject' => 'required|array|min:1|max:8',
        'applySubject.*' => 'exists:subjects,id',
    ])]
    public $applySubject = [];

    public function mount()
    {
        $student = auth()->user()->student;
        $this->advisor = $student?->advisorInstructor;

        $this->subjectForProgrammeEnrolled = $student?->programme?->subjects ?? collect();
    }

    public function render()
    {
        return view('livewire.advising');
    }

    public function submit()
    {
        $this->validate();
        $student = auth()->user()->student;
        if (!$student) {
            session()->flash('error', 'You are not authorized to perform this action.');
            return;
        }
        AdvisingModel::create([
            'student_id' => $student->student_id,
            'subject_ids' => $this->applySubject,
            'instructor_id' => $student->advisor,
            'status' => 'Pending',
        ]);

        $this->reset('applySubject');

        session()->flash('success', 'Advising submitted successfully.');
    }
}
