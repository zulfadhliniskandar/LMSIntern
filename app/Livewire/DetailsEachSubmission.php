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

    public $mark;
    public $feedback;

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
                $this->mark = $this->submission->mark;
                $this->feedback = $this->submission->feedback;
            }
        }

        if (!$this->assignment && $assignId) {
            $this->assignmentId = $assignId;
            $this->subjectId = $subjId;
            $this->assignment = Assignment::with('subject')->find($assignId);
            $this->subject = $this->assignment?->subject;
        }
    }

    public function saveGrade()
    {
        $this->validate([
            'mark' => 'nullable|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:2000',
        ]);

        if ($this->submission) {
            $this->submission->update([
                'mark' => ($this->mark !== '' && $this->mark !== null) ? (float)$this->mark : null,
                'feedback' => $this->feedback,
                'status' => 'graded',
            ]);
            $this->submission->refresh();
            session()->flash('message', 'Grade and feedback saved successfully!');
        }
    }

    public function saveAndNext()
    {
        $this->saveGrade();

        $nextSub = AssignmentSubmission::where('assignment_id', $this->assignmentId)
            ->where('id', '>', $this->submissionId)
            ->orderBy('id', 'asc')
            ->first();

        if ($nextSub) {
            return $this->redirect(route('submission-details', [
                'subject' => $this->subjectId,
                'assignment' => $this->assignmentId,
                'submission' => $nextSub->id,
            ]), navigate: true);
        } else {
            session()->flash('message', 'Grade saved! Reached the last student submission.');
        }
    }

    public function render()
    {
        $nextSub = $this->submissionId ? AssignmentSubmission::where('assignment_id', $this->assignmentId)->where('id', '>', $this->submissionId)->orderBy('id', 'asc')->first() : null;
        $prevSub = $this->submissionId ? AssignmentSubmission::where('assignment_id', $this->assignmentId)->where('id', '<', $this->submissionId)->orderBy('id', 'desc')->first() : null;

        return view('livewire.details-each-submission', [
            'nextSubmission' => $nextSub,
            'prevSubmission' => $prevSub,
        ]);
    }
}
