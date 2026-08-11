<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class check_plagiarism extends Model
{
    protected $table = 'check_plagiarisms';

    protected $fillable = [
        'assignment_submissions_id',
        'similarity_percentage',
        'matched_with',
        'status',
        'notes',
        'ai_status',
        'ai_probability',
        'ai_details',
        'ai_detector',
    ];

    protected $casts = [
        'matched_with' => 'array',
        'similarity_percentage' => 'float',
        'ai_probability' => 'float',
        'ai_details' => 'array',
    ];

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'assignment_submissions_id');
    }

    public function aiDetection()
    {
        return $this->hasOne(AiDetection::class, 'plagiarism_check_id');
    }
}
