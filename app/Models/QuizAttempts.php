<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempts extends Model
{
    protected $table = 'quiz_attempts';
    protected $fillable = [
        'quiz_id',
        'student_id',
        'score',
        'total_questions',
        'results',
    ];

    protected $casts = [
        'results' => 'array',
    ];

    public function quiz() : BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student() : BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
