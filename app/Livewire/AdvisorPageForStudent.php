<?php

namespace App\Livewire;

use App\Models\AppointmentWithAdvisor;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAppointmentRequest;


class AdvisorPageForStudent extends Component
{
    public $advisor;
    public $student;
    public $appointments = [];

    #[Validate('required|date|after_or_equal:today')]
    public $date;

    #[Validate('required')]
    public $time;

    #[Validate('required|string|min:5|max:1000')]
    public $note;

    public bool $showCancelModal = false;
    public $selectedAppointment = null;
    public string $cancel_note;

    public function mount()
    {
        $this->student = auth()->user()->student;

        if (!$this->student) {
            abort(403);
        }

        $this->advisor = $this->student->advisorInstructor;
        $this->loadAppointments();
    }

    public function loadAppointments()
    {
        if ($this->student) {
            $this->appointments = AppointmentWithAdvisor::where('student_id', $this->student->student_id)
                ->latest()
                ->get();
        }
    }

    public function sendRequest()
    {
        $this->validate();

        if (!$this->advisor) {
            session()->flash('error', 'No assigned academic advisor found.');
            return;
        }
        if($this->hasPendingAppointment()){
            session()->flash('error', 'You already have a pending appointment request.');
            return;
        }

        $appointment = AppointmentWithAdvisor::create([
            'student_id' => $this->student->student_id,
            'instructor_id' => $this->advisor->instructor_id,
            'date_appointment' => $this->date,
            'time_appointment' => $this->time,
            'note' => $this->note,
            'status' => 'pending',
        ]);

        if ($this->advisor->user) {
            Mail::to($this->advisor->user->email)->queue(new NewAppointmentRequest($appointment));

            $formattedTime = \Carbon\Carbon::parse($this->time)->format('g:i A');
            Notification::make()
                ->title('New Advising Appointment Request')
                ->body('Student ' . ($this->student->user->name ?? $this->student->student_id) . ' requested an appointment on ' . $this->date . ' at ' . $formattedTime . '.')
                ->info()
                ->sendToDatabase($this->advisor->user);
        }

        $this->reset(['date', 'time', 'note']);
        $this->loadAppointments();
        session()->flash('message', 'Appointment request submitted successfully!');
    }

    public function openModalCancel($appointmentId){
        $this->selectedAppointment = AppointmentWithAdvisor::findOrFail($appointmentId);
        if($this->selectedAppointment->status != 'pending'){
            session()->flash('error', 'Only Pending Appointment can be cancelled.');
            return;
        }
        $this->cancel_note = '';
        $this->resetValidation();
        $this->showCancelModal = true;
    }

    public function closeModalCancel(){
        $this->showCancelModal = false;
        $this->selectedAppointment = null;
        $this->cancel_note = '';
    }

    public function cancelAppointment()
    {
        $this->validate([
            'cancel_note' => 'required|string|min:5|max:1000',
        ]);
        $this->selectedAppointment->update([
            'status' => 'cancelled',
            'cancel_note' => $this->cancel_note,
        ]);
        $this->closeModalCancel();
        $this->loadAppointments();

        session()->flash('success', 'Appointment cancelled successfully.');
    }

    public function render()
    {
        return view('livewire.advisor-page-for-student');
    }

    public function hasPendingAppointment(): bool{
        if(!$this->student)
            return false;
        
        return AppointmentWithAdvisor::where('student_id', $this->student->student_id)
            ->where('status', 'pending')
            ->exists();
    }
}
