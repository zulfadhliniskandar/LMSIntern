<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Subjects;

class MaterialsForSubject extends Model
{
    protected $fillable = [
        'subject_id',
        'title',
        'description',
        'instruction',
        'file_path',
    ];
    
    protected $casts = [
        'file_path' => 'array',
    ];

    public function subject() : BelongsTo
    {
        return $this->belongsTo(Subjects::class);
    }

    public function getFullPath() : ?string
    {
        if ($this->file_path && is_array($this->file_path) && isset($this->file_path['path'])) {
            return asset('storage/' . $this->file_path['path']);
        }
        return null;
    }
}
