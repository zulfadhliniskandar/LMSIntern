<?php

namespace App\Livewire;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Subjects;
use Livewire\Component;

class DetailsEachSubmission extends Component
{
    public $submissionId;
    public $subjectId;
    public $assignmentId;

    public $submission;
    public $assignment;
    public $subject;
    public $student;

    public function mount($subject = null, $assignment = null, $submission = null)
    {
        $subId = is_object($submission) ? $submission->id : $submission;
        $assignId = is_object($assignment) ? $assignment->id : $assignment;
        $subjId = is_object($subject) ? $subject->id : $subject;

        if ($subId) {
            $this->submission = AssignmentSubmission::with(['student.user', 'student.programme', 'assignment.subject', 'checkPlagiarism'])->find($subId);
            if ($this->submission) {
                $this->submissionId = $this->submission->id;
                $this->assignment = $this->submission->assignment;
                $this->assignmentId = $this->assignment?->id;
                $this->subject = $this->assignment?->subject;
                $this->subjectId = $this->subject?->id;
                $this->student = $this->submission->student;
            }
        }

        if (!$this->assignment && $assignId) {
            $this->assignmentId = $assignId;
            $this->subjectId = $subjId;
            $this->assignment = Assignment::with('subject')->find($assignId);
            $this->subject = $this->assignment?->subject;
        }
    }

    public function render()
    {
        return view('livewire.details-each-submission');
    }
}
