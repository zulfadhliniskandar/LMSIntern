<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioVoicePreference extends Model
{
    protected $fillable = [
        'student_id', 'preferred_language', 'preferred_voice_name',
        'preferred_voice_gender', 'preferred_style', 'preferred_speech_rate',
        'preferred_pitch'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}

