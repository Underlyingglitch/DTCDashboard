<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Device extends Model implements Authenticatable
{
    use HasFactory, AuthenticableTrait;

    protected $fillable = ['name', 'device_id', 'type', 'loaded_page', 'settings', 'authenticated_user_id', 'last_seen', 'is_online', 'current_state', 'battery'];

    protected $casts = [
        'settings' => 'array',
        'current_state' => 'array',
        'battery' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($device) {
            if (env('DO_BROADCASTING', true)) event(new \App\Events\Device\DeviceUpdated($device));
        });
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'authenticated_user_id');
    // }
}
