<x-mail::message>
# New Course Material Upload

Hello! Your lecturer has uploaded new course materials for **{{ $courseSubject->name }}**.

**Material:** {{ $materialTitle }}

<x-mail::button :url="route('student-course-details', $courseSubject->id)" color="primary">
View Material
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>