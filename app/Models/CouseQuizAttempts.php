<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CouseQuizAttempts extends Model
{
    protected $table = 'course_quiz_attempts';

    protected $fillable = [
        'course_quiz_id',
        'user_id',
        'score',
        'total_questions',
        'started_at',
        'completed_at',
        'answers',
        'tab_switch_count',
        'violation_logs',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'answers' => 'array',
        'violation_logs' => 'array',
        'tab_switch_count' => 'integer',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(CourseQuizzes::class, 'course_quiz_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
