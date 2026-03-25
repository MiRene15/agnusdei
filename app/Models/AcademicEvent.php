<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicEvent extends Model
{
    protected $fillable = [
        'event_key',
        'event_name',
        'is_enabled',
        'description',
    ];

    public static function enabled(string $key): bool
    {
        return (bool) static::where('event_key', $key)->value('is_enabled');
    }
}