<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AiDetection extends Model
{
    protected $fillable = [
        'plagiarism_check_id',
        'ai_probability',
        'human_probability',
        'sentences_analyzed',
        'ai_sentences',
        'detection_method',
        'confidence_score',
        'detected_models', // ['gpt-4', 'gpt-3.5', 'claude']
        'details',
    ];

    protected $casts = [
        'detected_models' => 'json',
        'details' => 'json',
    ];

    public function plagiarismCheck()
    {
        return $this->belongsTo(Check_plagiarism::class);
    }
}