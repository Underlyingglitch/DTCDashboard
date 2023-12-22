<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Registration extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'match_day_id',
        'gymnast_id',
        'club_id',
        'niveau_id',
        'startnumber',
        'group_id',
        'team_id',
        'signed_off',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function wedstrijd()
    {
        return $this->belongsTo(Wedstrijd::class);
    }

    public function gymnast()
    {
        return $this->belongsTo(Gymnast::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'startnumber', 'startnumber');
    }
}
