<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Notifications\Notification;
use Livewire\WithFileUploads;
use App\Models\Subjects;
use App\Models\Assignment;
use App\Models\User;


class CreateAssignment extends Component
{
    use WithFileUploads;

    public Subjects $subject;

    public $title = '';
    public $description = '';
    public $date;
    public $time;
    public $due_date;
    
    
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
        $this->date = date('Y-m-d');
        $this->time = '23:59';
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
            'date' => 'required|date',
            'time' => 'required',
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
                $path = $file->storeAs('assignment', $customName, 'public');
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

        // Combine date and time into due_date timestamp
        if ($this->date && $this->time) {
            $this->due_date = \Carbon\Carbon::parse("{$this->date} {$this->time}")->format('Y-m-d H:i:s');
        } elseif ($this->date) {
            $this->due_date = \Carbon\Carbon::parse("{$this->date} 23:59:59")->format('Y-m-d H:i:s');
        } else {
            $this->due_date = now()->addWeek()->format('Y-m-d H:i:s');
        }

        Assignment::create([
            'subjects_id' => $this->subject->id,
            'title' => $this->title,
            'description' => $this->description ?: null,
            'due_date' => $this->due_date,
            'end_time' => $this->time ?: null,
            'file_path' => $filePathData,
        ]);

        if (auth()->user()?->isInstructor()) {
            $studentsToNotify = User::whereHas('student.subjectTaking', function ($query) {
                $query->where('subject_id', $this->subject->id);
            })->get();

            $formattedDueDate = \Carbon\Carbon::parse($this->due_date)->format('M d, Y - h:i A');

            Notification::make()
                ->title('New Assignment Uploaded')
                ->body('Your lecturer uploaded a new Assignment for ' . $this->subject->name . ': ' . $this->title . ' (Due: ' . $formattedDueDate . ').')
                ->success()
                ->sendToDatabase($studentsToNotify);
        }

        return $this->redirect(route('instructor-course-details', $this->subject->id), navigate: true);
    }


    public function render()
    {
        return view('livewire.create-assignment');
    }
}
