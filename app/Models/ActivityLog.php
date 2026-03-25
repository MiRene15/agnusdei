<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'module',
        'action',
        'target_type',
        'target_id',
        'description',
        'ip_address',
    ];

    public static function record(
        ?int $userId,
        string $module,
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?string $description = null,
        ?string $ipAddress = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'module' => $module,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
            'ip_address' => $ipAddress,
        ]);
    }
}