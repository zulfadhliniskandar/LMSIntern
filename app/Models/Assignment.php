<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'subjects_id',
        'title',
        'description',
        'due_date',
        'end_time',
        'file_path',
    ];

    protected $casts = [
        'file_path' => 'array',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subjects::class, 'subjects_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
