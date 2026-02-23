<?php

namespace App\Models;

use App\Casts\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class MatchDay extends Model implements Auditable
{
    use SoftDeletes, CascadeSoftDeletes, \OwenIt\Auditing\Auditable;

    protected $cascadeDeletes = ['wedstrijden', 'registrations', 'scores', 'declarations'];

    protected $fillable = [
        'name',
        'date',
        'location_id',
        'competition_id',
    ];

    protected $casts = [
        'date' => Date::class,
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function wedstrijden()
    {
        return $this->hasMany(Wedstrijd::class);
    }

    public function getNiveausAttribute()
    {
        return $this->wedstrijden->pluck('niveaus')->flatten()->unique('id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function declarations()
    {
        return $this->hasMany(Declaration::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->date->format('d-m-Y') . ') - ' . $this->competition->name;
    }

    public function getTeamsAttribute()
    {
        return Team::whereHas('registrations', function ($query) {
            $query->where('match_day_id', $this->id);
        })->get();
    }
}
