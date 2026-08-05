<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'user_id',
        'advisor',
        'programme_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advisorInstructor()
    {
        return $this->belongsTo(Instructor::class, 'advisor', 'instructor_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function subjectsTaken()
    {
        return $this->hasMany(SubjectTaken::class, 'student_id');
    }

    public function subjectTaking()
    {
        return $this->hasMany(subjectTaking::class, 'student_id');
    }

    public function appointments()
    {
        return $this->hasMany(AppointmentWithAdvisor::class, 'student_id', 'student_id');
    }
}
