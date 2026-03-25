<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'tuition_fee_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_no',
        'received_by',
        'received_by_user_id',
        'receipt_number',
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