<?php

namespace App\Livewire;

use App\Models\Subjects;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\MaterialsForSubject;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DetailsAndMaterialSubject extends Component
{
    use WithFileUploads;

    public User $user;
    public ?MaterialsForSubject $selectedMaterial = null;
    public Subjects $subject;
    public $materials = [];

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

    public function render()
    {
        return view('livewire.details-and-material-subject');
    }
}