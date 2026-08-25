<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CourseQuizOptions extends Model
{
    protected $table = 'course_quiz_options';

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(CourseQuizQuestions::class, 'question_id');
    }
}
