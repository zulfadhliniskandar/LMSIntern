<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class studentRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'address',
        'ic_passport',
        'age',
        'gender',
        'programme_id',
        'status',
        'document_path',
    ];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function getDocumentUrlAttribute()
    {
        return $this->document_path ? Storage::url($this->document_path) : null;
    }
}
