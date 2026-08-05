<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subjects;

class StudentSubjectDetails extends Component
{
    public Subjects $subject;

    public function mount(Subjects $subject)
    {
        $student = auth()->user()->student;
        if(!$student){
            abort(403);
        }
        $this->subject = $subject;
    }

    public function render()
    {
        return view('livewire.student-subject-details');
    }
}
