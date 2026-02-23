<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncTask extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($st) {
            if (!Setting::getValue('sync_enabled')) return;
            if (env('DO_BROADCASTING', true)) event(new \App\Events\DataSync\UpdateSyncStatus(1));
        });
    }

    protected $fillable = [
        'id',
        'model_type',
        'model_id',
        'operation',
        'data',
        'synced'
    ];
}
