<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_id',
        'grading_period',
        'seatwork_score',
        'quiz_score',
        'exam_score',
        'final_grade',
        'grade',
        'remarks',
    ];

    protected $casts = [
        'seatwork_score' => 'float',
        'quiz_score' => 'float',
        'exam_score' => 'float',
        'final_grade' => 'float',
        'grade' => 'float',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }
}
