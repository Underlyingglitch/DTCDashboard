<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProcessedScore extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'wedstrijd_id',
        'group_id',
        'toestel',
        'completed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($ps) {
            event(new \App\Events\ProcessedScoreUpdated($ps));
        });

        static::created(function ($ps) {
            event(new \App\Events\ProcessedScoreUpdated($ps));
        });
    }

    public function wedstrijd()
    {
        return $this->belongsTo(Wedstrijd::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
