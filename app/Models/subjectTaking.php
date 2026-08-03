<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Student;
use App\Models\Subjects;

class subjectTaking extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
    ];

    public function student() : BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function subject() : BelongsTo
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }
}
