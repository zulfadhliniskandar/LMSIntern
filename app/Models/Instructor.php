<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $primaryKey = 'instructor_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'instructor_id',
        'department_id',
        'Role',
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advisees()
    {
        return $this->hasMany(Student::class, 'advisor', 'instructor_id');
    }

    public function subjectsTaught()
    {
        return $this->hasMany(Subjects::class, 'instructor_id', 'instructor_id');
    }

    public function advisingRequests()
    {
        return $this->hasMany(advising::class, 'instructor_id', 'instructor_id');
    }
}
