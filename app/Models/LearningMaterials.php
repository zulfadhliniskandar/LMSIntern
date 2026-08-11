<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LearningMaterials extends Model
{
    protected $fillable = [
        'student_id',
        'title',
        'description',
        'original_file_path',
        'original_filename',
        'file_type',
        'file_size',
        'extracted_text',
        'simplified_text',
        'simplified_pdf_path',
        'simplification_status',
        'original_word_count',
        'simplified_word_count',
        'simplification_percentage',
        'original_reading_level',
        'original_readability_score',
        'simplified_reading_level',
        'simplified_readability_score',
    ];

    public function summary(): HasOne
    {
        return $this->hasOne(LearningMaterialSummaries::class, 'learning_materials_id');
    }

    public function learningMaterialSummaries(): HasOne
    {
        return $this->hasOne(LearningMaterialSummaries::class, 'learning_materials_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function quizzes() : HasMany
    {
        return $this->hasMany(Quiz::class, 'learning_materials_id');
    }
}
