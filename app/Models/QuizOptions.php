<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizOptions extends Model
{
    protected $table = 'quiz_options';
    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];

    public function question() : BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
