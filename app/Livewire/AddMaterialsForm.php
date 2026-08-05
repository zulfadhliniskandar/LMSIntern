<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Subjects;
use App\Models\MaterialsForSubject;
use App\Models\subjectTaking;
use App\Models\User;
use App\Mail\NewMaterialUploadMail;
use Illuminate\Support\Facades\Mail;

class AddMaterialsForm extends Component
{
    use WithFileUploads;

    public Subjects $subject;

    public $title = '';
    public $description = '';
    public $instruction = '';
    
    // Binding for newly uploaded files batch
    public $newFiles = [];

    // Accumulated list of all selected files
    public $files = [];

    // Multiple links array
    public $links = [];

    public function mount(Subjects $subject)
    {
        $instructor = auth()->user()->instructor;
        if(!$instructor){
            abort(403);
        }
        $this->subject = $subject;
        $this->links = [
            ['title' => '', 'url' => '']
        ];
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

    public function addLink()
    {
        $this->links[] = ['title' => '', 'url' => ''];
    }

    public function removeLink($index)
    {
        unset($this->links[$index]);
        $this->links = array_values($this->links);
    }

    public function removeFile($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files);
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instruction' => 'nullable|string',
            'files.*' => 'nullable|file|max:10240',
            'links.*.title' => 'nullable|string|max:255',
            'links.*.url' => 'nullable|url|max:500',
        ];
    }

    public function addMaterial()
    {
        $this->validate();

        $uploadedFiles = [];
        if ($this->files) {
            $i=0;
            foreach ($this->files as $index => $file) { 
                $i++;
                $extension = $file->getClientOriginalExtension();
                $customName = $this->subject->code . '_' . $this->title . '_(' . $i . ').' . $extension;
                $path = $file->storeAs('materials', $customName, 'public');
                $uploadedFiles[] = [
                    'original_name' => $customName,
                    'path' => $path,
                ];
            }
        }

        $validLinks = [];
        if ($this->links) {
            foreach ($this->links as $linkItem) {
                if (!empty($linkItem['url'])) {
                    $validLinks[] = [
                        'title' => !empty($linkItem['title']) ? $linkItem['title'] : $linkItem['url'],
                        'url' => $linkItem['url'],
                    ];
                }
            }
        }

        $filePathData = [
            'files' => $uploadedFiles,
            'links' => $validLinks,
        ];

        MaterialsForSubject::create([
            'subject_id' => $this->subject->id,
            'title' => $this->title,
            'description' => $this->description ?: null,
            'instruction' => $this->instruction ?: null,
            'file_path' => $filePathData,
        ]);
        if (auth()->user()?->isInstructor()) {
            $studentsToNotify = User::whereHas('student.subjectTaking', function ($query) {
                $query->where('subject_id', $this->subject->id);
            })->get();

            if ($studentsToNotify->isNotEmpty()) {
                foreach ($studentsToNotify as $student) {
                    Mail::to($student->email)->queue(new NewMaterialUploadMail($this->subject, $this->title));
                }
                Notification::make()
                    ->title('Course Material Added')
                    ->body('Your lecturer uploaded new materials for ' . $this->subject->name . ': ' . $this->title . '.')
                    ->success()
                    ->sendToDatabase($studentsToNotify);
            }
        }
        
        

        return $this->redirect(route('instructor-course-details', $this->subject->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.add-materials-form');
    }
}
