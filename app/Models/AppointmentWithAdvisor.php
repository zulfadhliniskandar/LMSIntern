<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentWithAdvisor extends Model
{
    protected $table = 'appointment_with_advisors';

    protected $fillable = [
        'student_id',
        'instructor_id',
        'date_appointment',
        'time_appointment',
        'note',
        'status',
        'cancel_note',
        'reject_note',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id', 'instructor_id');
    }
}
