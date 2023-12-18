<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function matchDays()
    {
        return $this->hasMany(MatchDay::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function getDatesAttribute()
    {
        return $this->matchDays->pluck('date');
    }
}
