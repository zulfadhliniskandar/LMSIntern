<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningMaterialSummaries extends Model
{
    protected $fillable = [
        'learning_materials_id',
        'key_points',
        'summary',
    ];

    protected $casts = [
        'key_points' => 'json'
    ];

    public function learningMaterial(): BelongsTo
    {
        return $this->belongsTo(LearningMaterials::class, 'learning_materials_id');
    }
}
