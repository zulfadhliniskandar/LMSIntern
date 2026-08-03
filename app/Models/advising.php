<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class advising extends Model
{
    protected $table = 'advisings';

    protected $fillable = [
        'student_id',
        'subject_ids',
        'instructor_id',
        'status',
    ];

    protected $casts = [
        'subject_ids' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }


    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
}
