<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamScore extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'match_day_id', 'toestel_scores', 'total_score'];
    protected $attributes = [
        'toestel_scores' => '0,0,0,0,0,0',
    ];

    public function getToestelScoresAttribute()
    {
        return explode(',', $this->attributes['toestel_scores']);
    }

    public function setToestelScoresAttribute($value)
    {
        $this->attributes['toestel_scores'] = implode(',', $value);
    }
}
