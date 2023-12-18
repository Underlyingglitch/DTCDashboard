<?php

namespace App\Models;

use App\Casts\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchDay extends Model
{
    use HasFactory;

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

    public function getNameAttribute()
    {
        return $this->location->name . " " . $this->date;
    }
}
