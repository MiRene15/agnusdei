<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'application_number',
        'lrn',
        'first_name',
        'last_name',
        'birth_date',
        'sex',
        'email',
        'phone',
        'address',
        'applying_for_grade',
        'previous_school',
        'status',
        'application_date',
        'remarks',
    ];

    public function requirements()
    {
        return $this->hasMany(AdmissionRequirement::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
}