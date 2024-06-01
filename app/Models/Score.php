<?php

namespace App\Models;

use App\Events\ScoreSaved;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Score extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasRelationships;

    protected $fillable = [
        'match_day_id',
        'startnumber',
        'toestel',
        'd',
        'e1',
        'e2',
        'e3',
        'n',
        'total',
        'place',
        'counted'
    ];

    public static function boot()
    {
        parent::boot();

        // static::creating(function ($score) {
        //     $es = array_filter([$score->e1, $score->e2, $score->e3]);
        //     $score->e = count($es) > 0 ? round(array_sum($es) / count($es), 3) : null;
        //     $total = $score->d > 0 ? (($score->d + (10 - $score->e)) - $score->n) : 0;
        //     if ($total < 0) $total = 0;
        //     $score->total = $total;
        // });

        // static::updating(function ($score) {
        //     $es = array_filter([$score->e1, $score->e2, $score->e3]);
        //     $score->e = count($es) > 0 ? round(array_sum($es) / count($es), 3) : null;
        //     $total = $score->d > 0 ? (($score->d + (10 - $score->e)) - $score->n) : 0;
        //     if ($total < 0) $total = 0;
        //     $score->total = $total;
        // });
    }

    public function match_day()
    {
        return $this->belongsTo(MatchDay::class);
    }

    public function getRegistrationAttribute()
    {
        return Registration::where([['startnumber', $this->startnumber], ['match_day_id', $this->match_day_id]])->first();
    }

    public function getEScoreAttribute()
    {
        if (is_null($this->d)) {
            return null;
        }
        if ($this->d == 0) {
            return 0;
        }
        return 10 - $this->e;
    }
}
