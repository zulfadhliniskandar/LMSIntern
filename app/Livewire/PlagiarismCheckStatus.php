<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\check_plagiarism;


class PlagiarismCheckStatus extends Component
{
    public $submissionId;
    public $check;

    public function mount($submissionId)
    {
        $this->submissionId = $submissionId;
        $this->loadCheck();
    }

    #[On('refreshCheck')]
    public function loadCheck()
    {
        $this->check = check_plagiarism::with('aiDetection')->where('assignment_submissions_id', $this->submissionId)->first();

        // If no check record exists yet, auto-trigger a check
        if (!$this->check && $this->submissionId) {
            $submission = \App\Models\AssignmentSubmission::find($this->submissionId);
            if ($submission) {
                \App\Jobs\CheckPlagiarism::dispatchSync($submission);
                $this->check = check_plagiarism::with('aiDetection')->where('assignment_submissions_id', $this->submissionId)->first();
            }
        }
    }

    public function retryCheck()
    {
        $submission = \App\Models\AssignmentSubmission::find($this->submissionId);
        if ($submission) {
            \App\Jobs\CheckPlagiarism::dispatchSync($submission);
            $this->loadCheck();
        }
    }

    public function render()
    {
        return view('livewire.plagiarism-check-status');
    }
}
