<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Team extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    public $fillable = ['name', 'competition_id', 'niveau_id', 'performing', 'counting'];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class)->orderBy('startnumber');
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function team_scores()
    {
        return $this->hasMany(TeamScore::class);
    }

    public function calculateScore()
    {
        // Calculate the team total score by summing the scores that have counted = true
        $scores = $this->registrations->pluck('scores')->flatten()->where('counted', true)->pluck('total');

        $this->total_score = $scores->sum();
        $this->save();
    }
}
