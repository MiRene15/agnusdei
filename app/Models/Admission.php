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
        'institutional_email',
        'phone',
        'address',
        'applying_for_grade',
        'previous_school',
        'status',
        'is_verified',
        'verified_at',
        'verified_by',
        'application_date',
        'remarks',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'application_date' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
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
