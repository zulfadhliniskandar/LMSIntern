<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';
    protected $fillable = [
        'quiz_id',
        'question_text',
        'explanation',
    ];

    public function quiz() : BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options() : HasMany
    {
        return $this->hasMany(QuizOptions::class, 'question_id');
    }

    

}
