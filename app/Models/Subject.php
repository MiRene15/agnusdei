<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = [
        'subject_code',
        'subject_name',
        'grade_level',
        'units',
    ];

    public function classes()
    {
        return $this->hasMany(Classes::class, 'subject_id');
    }
}