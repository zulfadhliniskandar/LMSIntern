<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CourseQuizzes extends Model
{
    protected $table = 'course_quizzes';
    
    protected $fillable = [
        'subject_id',
        'title',
        'description',
        'open_date',
        'close_date',
        'time_limit',
        'max_attempts',
        'isPublished',
        'total_questions',
    ];

    protected $casts = [
        'open_date' => 'datetime',
        'close_date' => 'datetime',
        'isPublished' => 'boolean',
        'max_attempts' => 'integer',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(CourseQuizQuestions::class, 'course_quiz_id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(CouseQuizAttempts::class, 'course_quiz_id');
    }
}
