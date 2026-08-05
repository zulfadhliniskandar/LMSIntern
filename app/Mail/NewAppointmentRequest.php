<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\AppointmentWithAdvisor;

class NewAppointmentRequest extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public AppointmentWithAdvisor $appointment)
    {
        $this->appointment->loadMissing(['student.user', 'instructor.user']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $studentName = $this->appointment->student?->user?->name ?? $this->appointment->student_id;
        return new Envelope(
            subject: 'New Appointment Request from Advisee: ' . $studentName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-appointemnt-request',
            with: [
                'appointment' => $this->appointment,
                'student_id' => $this->appointment->student_id,
                'date_appointment' => $this->appointment->date_appointment,
                'time_appointment' => $this->appointment->time_appointment,
                'note' => $this->appointment->note,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
