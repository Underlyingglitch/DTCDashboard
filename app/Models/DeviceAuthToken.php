<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceAuthToken extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'token', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function isValid()
    {
        return $this->expires_at && $this->expires_at->isFuture();
    }
}
