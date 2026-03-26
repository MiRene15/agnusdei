<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuitionFee extends Model
{
    protected $fillable = [
        'student_id',
        'school_year',
        'payment_plan',
        'total_amount',
        'down_payment_required',
        'monthly_payment',
        'previous_balance',
        'discount_type',
        'discount_rate',
        'discount_amount',
        'discount_notes',
        'total_due',
        'paid_amount',
        'balance',
        'due_date',
        'status',
        'is_downpayment_cleared',
        'carryover_approved',
        'carryover_approved_at',
        'carryover_approved_by',
        'carried_over_to_school_year',
        'voucher_status',
        'voucher_registrar_verified_at',
        'voucher_registrar_verified_by',
        'voucher_cashier_verified_at',
        'voucher_cashier_verified_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'down_payment_required' => 'decimal:2',
        'monthly_payment' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'discount_rate' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_due' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'due_date' => 'date',
        'is_downpayment_cleared' => 'boolean',
        'carryover_approved' => 'boolean',
        'carryover_approved_at' => 'datetime',
        'voucher_registrar_verified_at' => 'datetime',
        'voucher_cashier_verified_at' => 'datetime',
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
