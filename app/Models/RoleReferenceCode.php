<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleReferenceCode extends Model
{
    protected $fillable = [
        'role',
        'code',
        'description',
        'is_active',
        'max_uses',
        'used_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_uses' => 'integer',
        'used_count' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'reference_code_id');
    }

    public function canStillBeUsed(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (is_null($this->max_uses)) {
            return true;
        }

        return $this->used_count < $this->max_uses;
    }
}