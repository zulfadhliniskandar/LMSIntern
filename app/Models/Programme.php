<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'department_id',
        'totalFee',
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subjects::class, 'programme_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'programme_id');
    }
}
