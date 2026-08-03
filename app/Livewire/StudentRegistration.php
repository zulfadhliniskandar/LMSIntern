<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\studentRegistration as StudentRegistrationModel;
use Livewire\WithFileUploads;
use App\Models\Programme;
use App\Models\User;


class StudentRegistration extends Component
{
    use WithFileUploads;
    #[Validate('required|string|min:5|max:255')]
    public $full_name = '';
    #[Validate('required|email')]
    public $email = '';
    #[Validate('required|string|min:10|max:12')]
    public $phone = '';
    #[Validate('required|string|min:10|max:255')]
    public $address = '';
    #[Validate('required|string|min:9|max:12')]
    public $ic_passport = '';
    #[Validate('required|integer|min:18|max:100')]
    public $age = '';
    #[Validate('required|string|min:1|max:10')]
    public $gender = '';
    public $allProgrammes = [];
    #[Validate('required|exists:programmes,id')]
    public $programme_id = '';
    #[Validate('required|file|mimes:pdf,jpg,jpeg,png|max:2048')]
    public $document_path = '';

    public function mount()
    {
        if (auth()->user()->email != null)
        {
            $this->email = auth()->user()->email;
        }
        
        $this->allProgrammes = Programme::all();
    }
    public function render()
    {
        if (!auth()->user()->isNormalUser())
        {
            abort(403);
        }
        else
        {
            return view('livewire.student-registration');
        }
    }

    public function submit()
    {
        $validated = $this->validate();
        $extension = $this->document_path->getClientOriginalExtension();
        $fileName = 'REG_STUDENT_'.time() .'_'. str($this->full_name)->slug('_') . '.' . $extension;
        $documentPath = $this->document_path->storeAs('documents',$fileName, 'public');

        StudentRegistrationModel::create([
            'user_id' => auth()->id(),
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'ic_passport' => $this->ic_passport,
            'age' => $this->age,
            'gender' => $this->gender,
            'programme_id' => $this->programme_id,
            'document_path' => $documentPath,
        ]);

        $admins = User::whereHas('admin')->get();
        Notification::make()
            ->title('New Pending Student Registration')
            ->body($this->full_name . ' has submitted a new student registration form.')
            ->warning()
            ->sendToDatabase($admins);

        session()->flash('success', 'Student registered successfully!');
    }
}
