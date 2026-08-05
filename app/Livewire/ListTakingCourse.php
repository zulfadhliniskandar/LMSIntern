<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\subjectTaking;

class ListTakingCourse extends Component
{
    public $subjects;

    public function mount() {
        $student = auth()->user()->student;

        if(!$student){
            abort(403);
        }
        
        $student_id = $student->student_id;
        $this->subjects = subjectTaking::with('subject.instructor.user')->where('student_id', $student_id)->get();

        //dd($this->subjects);
    }
    public function render()
    {
        return view('livewire.list-taking-course');
    }
}
