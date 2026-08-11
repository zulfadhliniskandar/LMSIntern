<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Student;
use App\Models\LearningMaterials;

class AudioGeneration extends Model
{
    protected $fillable = [
        'student_id', 'learning_material_id', 'title', 'text_input', 'ai_script',
        'audio_file_path', 'audio_duration', 'file_size',
        'language', 'voice_name', 'voice_gender', 'style',
        'speech_rate', 'pitch', 'generation_mode', 'status', 'error_message'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function learningMaterial()
    {
        return $this->belongsTo(LearningMaterials::class);
    }

    public function recordPlay(){
        $this->update([
            'plays' => $this->plays + 1,
            'last_played_at' => now(),
        ]);
    }

    public function recordDownload(){
        $this->update([
            'downloads' => $this->downloads + 1,
        ]);
    }
}
