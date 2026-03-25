<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuitionFee extends Model
{
    protected $fillable = [
        'student_id',
        'school_year',
        'total_amount',
        'down_payment_required',
        'monthly_payment',
        'previous_balance',
        'total_due',
        'paid_amount',
        'balance',
        'due_date',
        'status',
        'is_downpayment_cleared',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}