<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Instructor;
use App\Models\Programme;
use App\Models\MaterialsForSubject;

class Subjects extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'instructor_id',
        'programme_id',
        'credit_hours',
        'fee',
    ];

    public function instructor() : BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id', 'instructor_id');
    }

    public function programme() : BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function materials() : HasMany
    {
        return $this->hasMany(MaterialsForSubject::class, 'subject_id');
    }

    public function messages() : HasMany
    {
        return $this->hasMany(SubjectMessage::class, 'subject_id');
    }
}
