<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'tuition_fee_id',
        'payment_date',
        'amount',
        'cash_tendered',
        'change_amount',
        'payment_method',
        'payment_label',
        'reference_no',
        'received_by',
        'received_by_user_id',
        'receipt_number',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cash_tendered' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function tuitionFee()
    {
        return $this->belongsTo(TuitionFee::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }
}
