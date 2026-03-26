<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Student extends Model
{
   protected $fillable = [
    'user_id',
    'parent_id',
    'admission_id',
    'student_number',
    'lrn',
    'first_name',
    'last_name',
    'birth_date',
    'gender',
    'email',
    'phone',
    'address',
    'grade_level',
    'section',
    'school_year',
    'status',
    'portal_access_status',
    'portal_unlocked_at',
    'is_transferred',
    'transferred_at',
    'transfer_notes',
    'ptc_completed',
    'ptc_completed_at',
];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function tuitionFees()
    {
        return $this->hasMany(TuitionFee::class, 'student_id');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}