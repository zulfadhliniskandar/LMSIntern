<x-mail::message>
    # New Appointment Request

    Hello! You have received a new consultation appointment request from your advisee.

    **Advisee Details:**
    - **Student ID:** {{ $appointment->student_id }}
    - **Student Name:** {{ $appointment->student?->user?->name ?? 'N/A' }}
    - **Preferred Date & Time:** {{ \Carbon\Carbon::parse($appointment->date_appointment)->format('F d, Y (l)') }} at
    {{ $appointment->time_appointment ? \Carbon\Carbon::parse($appointment->time_appointment)->format('g:i A') : 'N/A' }}

    **Consultation Agenda:**
    > {{ $appointment->note }}

    <x-mail::button :url="route('instructor-advising')" color="primary">
        Review Advising Portal
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>