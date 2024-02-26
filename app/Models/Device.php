<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ip', 'type', 'loaded_page', 'settings', 'authenticated_user_id', 'last_seen'];

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
