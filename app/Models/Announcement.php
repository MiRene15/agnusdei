<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'message',
        'audience',
        'posted_at',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];
}