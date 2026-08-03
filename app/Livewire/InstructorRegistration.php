<?php

namespace App\Livewire;

use App\Models\Departments;
use App\Models\InstructorRegistration as InstructorRegistrationModel;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Filament\Notifications\Notification;

class InstructorRegistration extends Component
{
    use WithFileUploads;

    public $allDepartments = [];

    #[Validate('required|string|min:5|max:255')]
    public $full_name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|string|min:10|max:15')]
    public $phone = '';

    #[Validate('required|string|min:10|max:255')]
    public $address = '';

    #[Validate('required|string|min:9|max:15')]
    public $ic_passport = '';

    #[Validate('required|integer|min:18|max:100')]
    public $age = '';

    #[Validate('required|string|in:Male,Female,Other')]
    public $gender = '';

    #[Validate('required|exists:departments,id')]
    public $department_id = '';

    #[Validate('required|file|mimes:pdf,jpg,jpeg,png|max:2048')]
    public $document_path;
    
    public function mount()
    {
        if (auth()->user()->email != null) {
            $this->email = auth()->user()->email;
        }
        $this->allDepartments = Departments::all();
    }

    public function submit()
    {
        $validated = $this->validate();
        $extension = $this->document_path->getClientOriginalExtension();
        $fileName = 'REG_INSTRUCTOR_'.time() .'_'. str($this->full_name)->slug('_') . '.' . $extension;
        $documentPath = $this->document_path->storeAs('documents',$fileName, 'public');

        InstructorRegistrationModel::create([
            'user_id' => auth()->id(),
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'ic_passport' => $this->ic_passport,
            'age' => $this->age,
            'gender' => $this->gender,
            'department_id' => $this->department_id,
            'document_path' => $documentPath,
        ]);

        $admins = User::whereHas('admin')->get();
        Notification::make()
            ->title('New Pending Instructor Registration')
            ->body($this->full_name . ' has submitted a new instructor registration form.')
            ->warning()
            ->sendToDatabase($admins);

        session()->flash('success', 'Instructor application submitted successfully!');
        
        // Reset form inputs except email & allDepartments
        $this->reset(['full_name', 'phone', 'address', 'ic_passport', 'age', 'gender', 'department_id', 'document_path']);
    }
    
    public function render()
    {
        if (!auth()->user()->isNormalUser()) {
            abort(403);
        }
        return view('livewire.instructor-registration');
    }
}
    