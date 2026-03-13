<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionRequirement extends Model
{
    protected $fillable = [
        'admission_id',
        'requirement_name',
        'submitted',
        'submitted_at',
        'status',
        'remarks',
        'file_path',
    ];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
}