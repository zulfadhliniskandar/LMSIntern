<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subjects;
use App\Models\Instructor;

class InstructorCourse extends Component
{
    public $instructorSubjects = [];

    public function mount()
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        if ($instructor) {
            $this->instructorSubjects = Subjects::where('instructor_id', $instructor->instructor_id)->get();
        }
    }
    public function render()
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        return view('livewire.instructor-course', [
            'instructorSubjects' => $this->instructorSubjects,
        ]);
    }
}
