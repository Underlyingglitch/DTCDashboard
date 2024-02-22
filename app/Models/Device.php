<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ip', 'type', 'settings', 'last_seen'];

    protected $casts = [
        'settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($device) {
            event(new \App\Events\Device\DeviceUpdated($device));
        });
    }
}
