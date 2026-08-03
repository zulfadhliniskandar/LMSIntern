<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departments extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function programmes()
    {
        return $this->hasMany(Programme::class, 'department_id');
    }
}
