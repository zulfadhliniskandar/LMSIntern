<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\SubjectMessage;
use Livewire\Attributes\On;
use Livewire\Component;

class CourseChat extends Component
{
    public $subjectId;
    public $message = "";

    public function mount($subjectId)
    {
        $this->subjectId = $subjectId;
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="fixed bottom-6 right-6 z-50">
            <button class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full shadow-xl opacity-90 cursor-pointer">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                <span class="text-xs font-bold tracking-wide">Course Discussion</span>
            </button>
        </div>
        HTML;
    }

    public function sendMessage()
    {
        $validated = $this->validate([
            'message' => 'required|min:1|max:2000',
        ]);
        
        $message = SubjectMessage::create([
            'user_id' => auth()->id(),
            'subject_id' => $this->subjectId,
            'message' => $this->message,
        ]);

        $this->reset('message');

        MessageSent::dispatch($message);

        $this->dispatch('messageSent');
    }

    #[On('echo-private:subject.{subjectId},MessageSent')]
    public function refreshMessages()
    {
        // Re-renders component automatically when a new message is broadcasted
    }

    public function togglePin($messageId)
    {
        $msg = SubjectMessage::where('subject_id', $this->subjectId)->findOrFail($messageId);

        if (auth()->user()?->isInstructor() || auth()->user()?->isAdmin()) {
            if (!$msg->is_pinned) {
                // Keep 1 main pinned message at a time per subject
                SubjectMessage::where('subject_id', $this->subjectId)->update(['is_pinned' => false]);
            }

            $msg->update(['is_pinned' => !$msg->is_pinned]);
        }
    }

    public function getPinnedMessageProperty()
    {
        return SubjectMessage::where('subject_id', $this->subjectId)
            ->where('is_pinned', true)
            ->with(['user.instructor', 'user.admin'])
            ->latest('updated_at')
            ->first();
    }

    public function getMessagesProperty()
    {
        return SubjectMessage::where('subject_id', $this->subjectId)
            ->with(['user.instructor', 'user.admin'])
            ->oldest()
            ->get();
    }

    public function render()
    {
        return view('livewire.course-chat', [
            'messages' => $this->messages,
            'pinnedMessage' => $this->pinnedMessage,
        ]);
    }
}
