<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleReferenceCode extends Model
{
    protected $fillable = [
        'role',
        'code',
        'subject_id',
        'section',
        'grade_level',
        'school_year',
        'semester',
        'created_by',
        'used_by',
        'is_used',
        'is_active',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}