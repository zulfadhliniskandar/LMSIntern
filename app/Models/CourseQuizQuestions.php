<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseQuizQuestions extends Model
{
    protected $table = 'course_quiz_questions';

    protected $fillable = [
        'course_quiz_id',
        'question',
        'explanation',
        'points',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(CourseQuizzes::class, 'course_quiz_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(CourseQuizOptions::class, 'question_id');
    }
}
