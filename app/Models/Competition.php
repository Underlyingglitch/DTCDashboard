<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Competition extends Model implements Auditable
{
    use SoftDeletes, CascadeSoftDeletes, \OwenIt\Auditing\Auditable;

    protected $cascadeDeletes = ['matchDays', 'teams'];

    protected $fillable = ['name'];

    public function match_days()
    {
        return $this->hasMany(MatchDay::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function getDatesAttribute()
    {
        return $this->match_days->pluck('date')->map(function ($date) {
            return $date->format('d-m-Y');
        });
    }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class);
    }
}
