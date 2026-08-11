<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';
    protected $fillable = [
        'learning_material_id',
        'title',
        'description',
        'total_questions',
    ];
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempts::class);
    }

    public function learningMaterial()
    {
        return $this->belongsTo(LearningMaterials::class, 'learning_material_id');
    }
}
