<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Wedstrijd extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'index',
        'match_day_id',
    ];

    public function baans($groups = null)
    {
        if ($groups == null) $groups = $this->groups;

        return $groups->pluck('baan')->unique()->count();
    }

    public function getGroupAmountAttribute()
    {
        return implode(', ', $this->groups->groupBy('baan')
            ->map(function ($item, $key) {
                return $item->count();
            })->toArray());
    }

    public function getNiveausListAttribute()
    {
        $niveaus = [];
        foreach ($this->niveaus as $niveau) {
            $niveaus[] = $niveau->full_name;
        }
        return implode(', ', $niveaus);
    }

    public function niveaus()
    {
        return $this->belongsToMany(Niveau::class);
    }

    public function match_day()
    {
        return $this->belongsTo(MatchDay::class);
    }

    public function competition()
    {
        return $this->match_day->competition();
    }

    public function teams()
    {
        return $this->competition->teams()->whereIn('niveau_id', $this->niveaus->pluck('id'));
    }

    public function registrations()
    {
        return $this->match_day->registrations()->whereIn('niveau_id', $this->niveaus->pluck('id'));
    }

    public function getGroupsAttribute()
    {
        return Group::find($this->registrations()->pluck('group_id')->unique());
    }
}
