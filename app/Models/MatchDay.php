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

    public function getNameAttribute()
    {
        return $this->location->name . " " . $this->date->format('d-m-Y');
    }
}
