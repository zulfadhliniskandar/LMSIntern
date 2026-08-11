<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\LearningMaterials;

class NotebooklmGeneration extends Model
{
    protected $fillable = [
        'student_id',
        'learning_material_id',
        'title',
        'text_input',
        'notebook_id',
        'audio_file_path',
        'audio_duration',
        'file_size',
        'status',
        'error_message',
        'plays',
        'downloads',
        'last_played_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function learningMaterial()
    {
        return $this->belongsTo(LearningMaterials::class);
    }

    public function recordPlay()
    {
        $this->update([
            'plays' => $this->plays + 1,
            'last_played_at' => now(),
        ]);
    }

    public function recordDownload()
    {
        $this->update([
            'downloads' => $this->downloads + 1,
        ]);
    }
}
