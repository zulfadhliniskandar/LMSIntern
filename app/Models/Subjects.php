<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id', 'instructor_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }
}
