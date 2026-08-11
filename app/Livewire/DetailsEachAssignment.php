<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Livewire\WithFileUploads;
use App\Jobs\CheckPlagiarism;


class DetailsEachAssignment extends Component
{
    use WithFileUploads;

    public $assignment;
    public $submission;

    public $newFiles = [];

    // Accumulated list of all selected files
    public $files = [];

    public function mount($subject = null, $assignment = null)
    {
        $targetId = $assignment ?? $subject;
        if (is_object($targetId)) {
            $targetId = $targetId->id;
        }

        $this->assignment = Assignment::with(['subject', 'submissions.student.user'])->findOrFail($targetId);

        $studentId = auth()->user()->student?->student_id;
        if ($studentId) {
            $this->submission = AssignmentSubmission::where('assignment_id', $this->assignment->id)
                ->where('student_id', $studentId)
                ->first();
        }
    }

    public function hasSubmitted() : bool {
        if ($this->submission) {
            return true;
        }
        $studentId = auth()->user()->student?->student_id;
        if (!$studentId) return false;
        $this->submission = AssignmentSubmission::where('assignment_id', $this->assignment->id)->where('student_id', $studentId)->first();
        return $this->submission !== null;
    }

    public function updatedNewFiles()
    {
        $this->validate([
            'newFiles.*' => 'file|max:10240',
        ]);

        if (is_array($this->newFiles)) {
            foreach ($this->newFiles as $file) {
                $this->files[] = $file;
            }
        } elseif ($this->newFiles) {
            $this->files[] = $this->newFiles;
        }

        $this->reset('newFiles');
    }

    public function removeFile($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files);
    }

    protected function rules()
    {
        return [
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:10240',
        ];
    }

    public function submitAssignment()
    {
        $this->validate();

        $uploadedFiles = [];
        if ($this->files) {
            $i = 0;
            $subjectCode = $this->assignment->subject?->code ?? 'SUBJ';
            $assignmentTitle = $this->assignment->title ?? 'Assignment';
            $studentId = auth()->user()->student?->student_id;
            foreach ($this->files as $index => $file) { 
                $i++;
                $extension = $file->getClientOriginalExtension();
                $customName = $subjectCode . ' - ' . $studentId . '_' . $assignmentTitle . '_(' . $i . ').' . $extension;
                $path = $file->storeAs('assignment_submissions', $customName, 'public');
                $uploadedFiles[] = [
                    'original_name' => $customName,
                    'path' => $path,
                ];
            }
        }

        $filePathData = [
            'files' => $uploadedFiles,
        ];

        $studentId = auth()->user()->student?->student_id;

        $submission = AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $studentId,
            'submission_text' => null,
            'status' => 'Submitted',
            'file_path' => $filePathData,
        ]);

        CheckPlagiarism::dispatch($submission);

        $this->dispatch('submissionSuccess', message: 'Assignment Submitted! Plagiarism check in progress');

        session()->flash('success', 'Assignment submitted successfully!');

        return $this->redirect(route('course-assignment-details', [$this->assignment->subjects_id, $this->assignment->id]), navigate: true);
    }
    public function render()
    {
        return view('livewire.details-each-assignment');
    }
    public function removeSubmission()
    {
        if ($this->submission) {
            if (is_array($this->submission->file_path)) {
                $filesToDelete = $this->submission->file_path['files'] ?? (isset($this->submission->file_path[0]) ? $this->submission->file_path : []);
                foreach ($filesToDelete as $file) {
                    $path = $file['path'] ?? $file['file_path'] ?? null;
                    if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                    }
                }
            }
            $this->submission->delete();
            $this->submission = null;
        }

        session()->flash('success', 'Assignment submission removed successfully.');
        return $this->redirect(route('course-assignment-details', [$this->assignment->subjects_id, $this->assignment->id]), navigate: true);
    }
}
