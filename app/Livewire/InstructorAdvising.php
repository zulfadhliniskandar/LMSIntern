<?php

namespace App\Livewire;

use App\Models\subjectTaking;
use Livewire\Component;
use App\Models\advising as AdvisingModel;
use Filament\Notifications\Notification;

class InstructorAdvising extends Component
{
    public ?AdvisingModel $selectedRequest = null;
    public $advisingRequests = [];
    public bool $showDetailsModal = false;

    public function showRequestDetails($id){
        $this->selectedRequest = AdvisingModel::with(['student.user', 'student.programme'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function mount()
    {
        $instructor = auth()->user()->instructor;

        $this->advisingRequests = $instructor
            ? AdvisingModel::where('instructor_id', $instructor->instructor_id)->latest()->get()
            : collect();
    }

    public function approve($id)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor) {
            session()->flash('error', 'Instructor record not found.');
            return;
        }

        $request = AdvisingModel::findOrFail($id);

        if ($request->instructor_id !== $instructor->instructor_id) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        $request->update([
            'status' => 'Approved',
        ]);
        $subjectIds = is_string($request->subject_ids) ? json_decode($request->subject_ids, true) : $request->subject_ids;
        foreach ($subjectIds as $subjectId) {
            subjectTaking::create([
                'student_id' => $request->student_id,
                'subject_id' => $subjectId,
            ]);
        }
        $userToNotify = $request->student->user;
        Notification::make()
            ->title('Advising request approved successfully.')
            ->body('Your advising request has been approved.')
            ->success()
            ->sendToDatabase($userToNotify);

        $this->advisingRequests = AdvisingModel::where('instructor_id', $instructor->instructor_id)->latest()->get();

        session()->flash('success', 'Advising request approved successfully.');
    }
     public function reject($id)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor) {
            session()->flash('error', 'Instructor record not found.');
            return;
        }

        $request = AdvisingModel::findOrFail($id);

        if ($request->instructor_id !== $instructor->instructor_id) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        $request->update([
            'status' => 'Rejected',
        ]);
        $userToNotify = $request->student->user;
        Notification::make()
            ->title('Advising request rejected')
            ->body('Your advising request has been rejected.')
            ->danger()
            ->sendToDatabase($userToNotify);

        $this->advisingRequests = AdvisingModel::where('instructor_id', $instructor->instructor_id)->latest()->get();

        session()->flash('success', 'Advising request rejected successfully.');
    }

    public function render()
    {
        return view('livewire.instructor-advising', [
            'advisingRequests' => $this->advisingRequests,
        ]);
    }
}
