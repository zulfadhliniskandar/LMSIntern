<?php

namespace App\Livewire;

use App\Models\AppointmentWithAdvisor;
use Filament\Notifications\Notification;
use Livewire\Component;
use App\Models\User;


class AdviseeProfile extends Component
{
    public $advisee;
    public $pendingAppointment;

    public $showCurrentSubjects = true;
    public $showCompletedSubjects = false;
    public $activeTab = 'current'; // 'current', 'completed', or 'all'

    public $currentSubjectsCount = 0;
    public $completedSubjectsCount = 0;

    public function mount($studentId) {
        $this->advisee = User::with(['student.programme.department'])->findOrFail($studentId);
        
        $actualStudentId = $this->advisee->student?->student_id ?? $studentId;

        $this->pendingAppointment = AppointmentWithAdvisor::where(function ($query) use ($studentId, $actualStudentId) {
            $query->where('student_id', $actualStudentId)
                  ->orWhere('student_id', $studentId);
        })->where('status', 'pending')->first();

        $this->currentSubjectsCount = \App\Models\subjectTaking::where(function ($q) use ($studentId, $actualStudentId) {
            $q->where('student_id', $actualStudentId)
              ->orWhereHas('student', fn ($sq) => $sq->where('user_id', $studentId));
        })->count();

        $this->completedSubjectsCount = \App\Models\SubjectTaken::where(function ($q) use ($studentId, $actualStudentId) {
            $q->where('student_id', $actualStudentId)
              ->orWhereHas('student', fn ($sq) => $sq->where('user_id', $studentId));
        })->count();
    }

    public function setTab(string $tab) {
        $this->activeTab = $tab;
        if ($tab === 'current') {
            $this->showCurrentSubjects = true;
            $this->showCompletedSubjects = false;
        } elseif ($tab === 'completed') {
            $this->showCurrentSubjects = false;
            $this->showCompletedSubjects = true;
        } elseif ($tab === 'all') {
            $this->showCurrentSubjects = true;
            $this->showCompletedSubjects = true;
        }
    }

    public function render()
    {
        return view('livewire.advisee-profile');
    }

    public function confirmAppointment() {
        if ($this->pendingAppointment) {
            $this->pendingAppointment->update(['status' => 'confirmed']);
            $this->pendingAppointment = null;

            Notification::make()
                ->title('Appointment Confirmed')
                ->body('Appointment confirmed successfully.')
                ->success()
                ->send();

            Notification::make()
                ->title('Appointment Confirmed')
                ->body('Your advisor has confirmed your appointment request.')
                ->success()
                ->sendToDatabase($this->advisee);
            session()->flash('success', 'Appointment confirmed successfully.');
            return $this->redirect(route('student-profile', $this->advisee));
            
        }
    }

    public function rejectAppointment() {
        if ($this->pendingAppointment) {
            $this->pendingAppointment->update(['status' => 'rejected']);
            $this->pendingAppointment = null;

            Notification::make()
                ->title('Appointment Rejected')
                ->body('Appointment rejected successfully.')
                ->success()
                ->send();

            Notification::make()
                ->title('Appointment Rejected')
                ->body('Your advisor has declined your appointment request.')
                ->danger()
                ->sendToDatabase($this->advisee);
            session()->flash('success', 'Appointment rejected successfully.');
            $this->redirect(route('student-profile', $this->advisee));
            
        }
    }

    public function toggleCurrentSubjects() : bool {
        $this->showCurrentSubjects = !$this->showCurrentSubjects;
        return $this->showCurrentSubjects;
    }

    public function toggleCompletedSubjects() : bool {
        $this->showCompletedSubjects = !$this->showCompletedSubjects;
        return $this->showCompletedSubjects;
    }
}
