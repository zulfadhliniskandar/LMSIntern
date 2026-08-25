<?php

namespace App\Livewire;

use App\Models\Subjects;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\MaterialsForSubject;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Assignment;
use App\Models\CourseQuizzes;


class DetailsAndMaterialSubject extends Component
{
    use WithFileUploads;

    public User $user;
    public ?MaterialsForSubject $selectedMaterial = null;
    public Subjects $subject;
    public $materials = [];
    public $assignment = [];
    public $quizzes = [];

    public bool $showEditModal = false;

    // Edit form fields
    public $editTitle = '';
    public $editDescription = '';
    public $editInstruction = '';
    public $editFiles = [];
    public $editLinks = [];

    // New file uploads during edit
    public $newFiles = [];
    public $accumulatedNewFiles = [];

    // Assignment Edit fields
    public ?Assignment $selectedAssignment = null;
    public bool $showEditAssignmentModal = false;
    public $editAssignmentTitle = '';
    public $editAssignmentDescription = '';
    public $editAssignmentDate = '';
    public $editAssignmentTime = '';
    public $editAssignmentFiles = [];
    public $editAssignmentLinks = [];
    public $newAssignmentFiles = [];
    public $accumulatedNewAssignmentFiles = [];

    // Quiz Edit & Results fields
    public ?CourseQuizzes $selectedQuiz = null;
    public bool $showEditQuizModal = false;
    public $editQuizTitle = '';
    public $editQuizDescription = '';
    public $editQuizOpenDate = '';
    public $editQuizCloseDate = '';
    public $editQuizTimeLimit = null;
    public $editQuizMaxAttempts = 1;
    public $editQuizIsPublished = false;

    public ?CourseQuizzes $selectedQuizForResults = null;
    public bool $showQuizResultsModal = false;

    public function mount(Subjects $subject)
    {
        $instructor = auth()->user()->instructor;
        $student = auth()->user()->student;
        
        if(!$instructor && !$student){
            abort(403);
        }
        $this->user = auth()->user();
        $this->subject = $subject;
        $this->loadMaterials();
        $this->loadAssignment();
        $this->loadQuizzes();
    }

    public function loadMaterials()
    {
        $instructor = auth()->user()->instructor;
        $student = auth()->user()->student;
        
        if(!$instructor && !$student){
            abort(403);
        }
        $this->materials = MaterialsForSubject::where('subject_id', $this->subject->id)->latest()->get();
    }
    public function loadAssignment()
    {
        $instructor = auth()->user()->instructor;
        $student = auth()->user()->student;
        
        if(!$instructor && !$student){
            abort(403);
        }
        $this->assignment = Assignment::where('subjects_id', $this->subject->id)->latest()->get();
    }

    public function loadQuizzes()
    {
        $instructor = auth()->user()->instructor;
        $student = auth()->user()->student;
        
        if(!$instructor && !$student){
            abort(403);
        }
        $query = CourseQuizzes::where('subject_id', $this->subject->id)
            ->with(['attempts' => function ($q) {
                $q->with('user');
            }]);

        if ($student && !$instructor) {
            $query->where('isPublished', true);
        }

        $this->quizzes = $query->latest()->get();
    }

    public function openModal($id)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->selectedMaterial = MaterialsForSubject::findOrFail($id);
        $this->editTitle = $this->selectedMaterial->title;
        $this->editDescription = $this->selectedMaterial->description ?? '';
        $this->editInstruction = $this->selectedMaterial->instruction ?? '';

        $filePathData = $this->selectedMaterial->file_path;
        if (is_array($filePathData)) {
            $this->editFiles = $filePathData['files'] ?? (isset($filePathData['path']) ? [$filePathData] : []);
            $this->editLinks = $filePathData['links'] ?? (isset($filePathData['url']) ? [$filePathData] : []);
        } else {
            $this->editFiles = [];
            $this->editLinks = [];
        }

        if (empty($this->editLinks)) {
            $this->editLinks = [['title' => '', 'url' => '']];
        }

        $this->newFiles = [];
        $this->accumulatedNewFiles = [];
        $this->showEditModal = true;
    }

    public function addEditLink()
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->editLinks[] = ['title' => '', 'url' => ''];
    }

    public function removeEditLink($index)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        unset($this->editLinks[$index]);
        $this->editLinks = array_values($this->editLinks);
    }

    public function removeExistingFile($index)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        unset($this->editFiles[$index]);
        $this->editFiles = array_values($this->editFiles);
    }

    public function updatedNewFiles()
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->validate([
            'newFiles.*' => 'file|max:10240',
        ]);

        if (is_array($this->newFiles)) {
            foreach ($this->newFiles as $file) {
                $this->accumulatedNewFiles[] = $file;
            }
        } elseif ($this->newFiles) {
            $this->accumulatedNewFiles[] = $this->newFiles;
        }

        $this->reset('newFiles');
    }

    public function removeNewFile($index)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        unset($this->accumulatedNewFiles[$index]);
        $this->accumulatedNewFiles = array_values($this->accumulatedNewFiles);
    }

    public function updateMaterial()
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editInstruction' => 'nullable|string',
            'accumulatedNewFiles.*' => 'nullable|file|max:10240',
            'editLinks.*.title' => 'nullable|string|max:255',
            'editLinks.*.url' => 'nullable|url|max:500',
        ]);

        if (!$this->selectedMaterial) {
            return;
        }

        // Store existing files
        $finalFiles = $this->editFiles;

        // Delete physical files from disk that were removed during editing
        $originalFilePathData = $this->selectedMaterial->file_path;
        $originalFiles = is_array($originalFilePathData)
            ? ($originalFilePathData['files'] ?? (isset($originalFilePathData['path']) ? [$originalFilePathData] : []))
            : [];

        $remainingPaths = array_column($finalFiles, 'path');

        foreach ($originalFiles as $oldFile) {
            if (isset($oldFile['path']) && !in_array($oldFile['path'], $remainingPaths)) {
                if (Storage::disk('public')->exists($oldFile['path'])) {
                    Storage::disk('public')->delete($oldFile['path']);
                }
            }
        }

        // Store any newly uploaded files
        if ($this->accumulatedNewFiles) {
            $i = count($finalFiles);
            foreach ($this->accumulatedNewFiles as $file) {
                $i++;
                $extension = $file->getClientOriginalExtension();
                $customName = $this->subject->code . '_' . $this->editTitle . '_(' . $i . ').' . $extension;
                $path = $file->storeAs('materials', $customName, 'public');
                $finalFiles[] = [
                    'original_name' => $customName,
                    'path' => $path,
                ];
            }
        }

        // Filter valid links
        $validLinks = [];
        if ($this->editLinks) {
            foreach ($this->editLinks as $linkItem) {
                if (!empty($linkItem['url'])) {
                    $validLinks[] = [
                        'title' => !empty($linkItem['title']) ? $linkItem['title'] : $linkItem['url'],
                        'url' => $linkItem['url'],
                    ];
                }
            }
        }

        $this->selectedMaterial->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription ?: null,
            'instruction' => $this->editInstruction ?: null,
            'file_path' => [
                'files' => $finalFiles,
                'links' => $validLinks,
            ],
        ]);

        $this->showEditModal = false;
        $this->loadMaterials();
        session()->flash('success', 'Course material updated successfully.');
    }

    public function deleteMaterial($id)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $material = MaterialsForSubject::find($id);
        if ($material) {
            if (is_array($material->file_path)) {
                $filesToDelete = $material->file_path['files'] ?? [];
                if (isset($material->file_path['file'])) {
                    $filesToDelete[] = $material->file_path['file'];
                }
                if (isset($material->file_path['path'])) {
                    $filesToDelete[] = $material->file_path;
                }

                foreach ($filesToDelete as $file) {
                    if (!empty($file['path']) && Storage::disk('public')->exists($file['path'])) {
                        Storage::disk('public')->delete($file['path']);
                    }
                }
            }
            $material->delete();
            $this->loadMaterials();
            session()->flash('success', 'Material deleted successfully');
        }
    }

    public function deleteAssignment($id)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $assignment = Assignment::find($id);
        if ($assignment) {
            if (is_array($assignment->file_path)) {
                $filesToDelete = $assignment->file_path['files'] ?? [];
                foreach ($filesToDelete as $file) {
                    if (!empty($file['path']) && Storage::disk('public')->exists($file['path'])) {
                        Storage::disk('public')->delete($file['path']);
                    }
                }
            }
            $assignment->delete();
            $this->loadAssignment();
            session()->flash('success', 'Assignment deleted successfully.');
        }
    }

    public function openAssignmentModal($id)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->selectedAssignment = Assignment::findOrFail($id);
        $this->editAssignmentTitle = $this->selectedAssignment->title;
        $this->editAssignmentDescription = $this->selectedAssignment->description ?? '';

        if ($this->selectedAssignment->due_date) {
            $carbonDate = \Carbon\Carbon::parse($this->selectedAssignment->due_date);
            $this->editAssignmentDate = $carbonDate->format('Y-m-d');
            $this->editAssignmentTime = $carbonDate->format('H:i');
        } else {
            $this->editAssignmentDate = date('Y-m-d');
            $this->editAssignmentTime = '23:59';
        }

        $filePathData = $this->selectedAssignment->file_path;
        if (is_array($filePathData)) {
            $this->editAssignmentFiles = $filePathData['files'] ?? [];
            $this->editAssignmentLinks = $filePathData['links'] ?? [];
        } else {
            $this->editAssignmentFiles = [];
            $this->editAssignmentLinks = [];
        }

        if (empty($this->editAssignmentLinks)) {
            $this->editAssignmentLinks = [['title' => '', 'url' => '']];
        }

        $this->newAssignmentFiles = [];
        $this->accumulatedNewAssignmentFiles = [];
        $this->showEditAssignmentModal = true;
    }

    public function addEditAssignmentLink()
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->editAssignmentLinks[] = ['title' => '', 'url' => ''];
    }

    public function removeEditAssignmentLink($index)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        unset($this->editAssignmentLinks[$index]);
        $this->editAssignmentLinks = array_values($this->editAssignmentLinks);
    }

    public function removeExistingAssignmentFile($index)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        unset($this->editAssignmentFiles[$index]);
        $this->editAssignmentFiles = array_values($this->editAssignmentFiles);
    }

    public function updatedNewAssignmentFiles()
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->validate([
            'newAssignmentFiles.*' => 'file|max:10240',
        ]);

        if (is_array($this->newAssignmentFiles)) {
            foreach ($this->newAssignmentFiles as $file) {
                $this->accumulatedNewAssignmentFiles[] = $file;
            }
        } elseif ($this->newAssignmentFiles) {
            $this->accumulatedNewAssignmentFiles[] = $this->newAssignmentFiles;
        }

        $this->reset('newAssignmentFiles');
    }

    public function removeNewAssignmentFile($index)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        unset($this->accumulatedNewAssignmentFiles[$index]);
        $this->accumulatedNewAssignmentFiles = array_values($this->accumulatedNewAssignmentFiles);
    }

    public function updateAssignment()
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->validate([
            'editAssignmentTitle' => 'required|string|max:255',
            'editAssignmentDescription' => 'nullable|string',
            'editAssignmentDate' => 'required|date',
            'editAssignmentTime' => 'required',
            'accumulatedNewAssignmentFiles.*' => 'nullable|file|max:10240',
            'editAssignmentLinks.*.title' => 'nullable|string|max:255',
            'editAssignmentLinks.*.url' => 'nullable|url|max:500',
        ]);

        if (!$this->selectedAssignment) {
            return;
        }

        // Store existing files
        $finalFiles = $this->editAssignmentFiles;

        // Delete physical files from disk that were removed during editing
        $originalFilePathData = $this->selectedAssignment->file_path;
        $originalFiles = is_array($originalFilePathData)
            ? ($originalFilePathData['files'] ?? [])
            : [];

        $remainingPaths = array_column($finalFiles, 'path');

        foreach ($originalFiles as $oldFile) {
            if (isset($oldFile['path']) && !in_array($oldFile['path'], $remainingPaths)) {
                if (Storage::disk('public')->exists($oldFile['path'])) {
                    Storage::disk('public')->delete($oldFile['path']);
                }
            }
        }

        // Store any newly uploaded files
        if ($this->accumulatedNewAssignmentFiles) {
            $i = count($finalFiles);
            foreach ($this->accumulatedNewAssignmentFiles as $file) {
                $i++;
                $extension = $file->getClientOriginalExtension();
                $customName = $this->subject->code . '_' . $this->editAssignmentTitle . '_(' . $i . ').' . $extension;
                $path = $file->storeAs('assignment', $customName, 'public');
                $finalFiles[] = [
                    'original_name' => $customName,
                    'path' => $path,
                ];
            }
        }

        // Filter valid links
        $validLinks = [];
        if ($this->editAssignmentLinks) {
            foreach ($this->editAssignmentLinks as $linkItem) {
                if (!empty($linkItem['url'])) {
                    $validLinks[] = [
                        'title' => !empty($linkItem['title']) ? $linkItem['title'] : $linkItem['url'],
                        'url' => $linkItem['url'],
                    ];
                }
            }
        }

        // Combine date and time into due_date timestamp
        $dueDate = null;
        if ($this->editAssignmentDate && $this->editAssignmentTime) {
            $dueDate = \Carbon\Carbon::parse("{$this->editAssignmentDate} {$this->editAssignmentTime}")->format('Y-m-d H:i:s');
        } elseif ($this->editAssignmentDate) {
            $dueDate = \Carbon\Carbon::parse("{$this->editAssignmentDate} 23:59:59")->format('Y-m-d H:i:s');
        }

        $this->selectedAssignment->update([
            'title' => $this->editAssignmentTitle,
            'description' => $this->editAssignmentDescription ?: null,
            'due_date' => $dueDate,
            'end_time' => $this->editAssignmentTime ?: null,
            'file_path' => [
                'files' => $finalFiles,
                'links' => $validLinks,
            ],
        ]);

        $this->showEditAssignmentModal = false;
        $this->loadAssignment();
        session()->flash('success', 'Assignment updated successfully.');
    }

    public function openQuizModal($id)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->selectedQuiz = CourseQuizzes::findOrFail($id);
        $this->editQuizTitle = $this->selectedQuiz->title;
        $this->editQuizDescription = $this->selectedQuiz->description ?? '';
        $this->editQuizOpenDate = $this->selectedQuiz->open_date ? $this->selectedQuiz->open_date->format('Y-m-d\TH:i') : '';
        $this->editQuizCloseDate = $this->selectedQuiz->close_date ? $this->selectedQuiz->close_date->format('Y-m-d\TH:i') : '';
        $this->editQuizTimeLimit = $this->selectedQuiz->time_limit;
        $this->editQuizMaxAttempts = $this->selectedQuiz->max_attempts ?? 1;
        $this->editQuizIsPublished = (bool) $this->selectedQuiz->isPublished;
        $this->showEditQuizModal = true;
    }

    public function updateQuiz()
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->validate([
            'editQuizTitle' => 'required|string|max:255',
            'editQuizDescription' => 'nullable|string',
            'editQuizOpenDate' => 'required|date',
            'editQuizCloseDate' => 'required|date|after:editQuizOpenDate',
            'editQuizTimeLimit' => 'nullable|integer|min:1|max:600',
            'editQuizMaxAttempts' => 'required|integer|min:1|max:100',
        ]);

        if (!$this->selectedQuiz) {
            return;
        }

        $this->selectedQuiz->update([
            'title' => $this->editQuizTitle,
            'description' => $this->editQuizDescription ?: null,
            'open_date' => $this->editQuizOpenDate,
            'close_date' => $this->editQuizCloseDate,
            'time_limit' => $this->editQuizTimeLimit ? (int) $this->editQuizTimeLimit : null,
            'max_attempts' => (int) $this->editQuizMaxAttempts,
            'isPublished' => $this->editQuizIsPublished,
        ]);

        $this->showEditQuizModal = false;
        $this->loadQuizzes();
        session()->flash('success', 'Quiz updated successfully.');
    }

    public function deleteQuiz($id)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $quiz = CourseQuizzes::find($id);
        if ($quiz) {
            $quiz->delete();
            $this->loadQuizzes();
            session()->flash('success', 'Quiz deleted successfully.');
        }
    }

    public function togglePublishQuiz($id)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $quiz = CourseQuizzes::find($id);
        if ($quiz) {
            $quiz->update(['isPublished' => !$quiz->isPublished]);
            $this->loadQuizzes();
            session()->flash('success', 'Quiz ' . ($quiz->isPublished ? 'published' : 'unpublished') . ' successfully.');
        }
    }

    public function openQuizResultsModal($id)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->selectedQuizForResults = CourseQuizzes::with(['attempts.user'])->findOrFail($id);
        $this->showQuizResultsModal = true;
    }

    public function render()
    {
        return view('livewire.details-and-material-subject');
    }
}