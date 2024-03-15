<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScoreCorrection extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'startnumber',
        'score_id',
        'd',
        'e1',
        'e2',
        'e3',
        'n',
        'total',
        'approved',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($scoreCorrection) {
            event(new \App\Events\Jury\ScoreCorrectionAdded($scoreCorrection, 'create'));
        });

        static::updated(function ($scoreCorrection) {
            event(new \App\Events\Jury\ScoreCorrectionAdded($scoreCorrection, 'update'));
        });

        static::deleted(function ($scoreCorrection) {
            event(new \App\Events\Jury\ScoreCorrectionAdded($scoreCorrection, 'delete'));
        });
    }

    public function score()
    {
        return $this->belongsTo(Score::class);
    }

    public function getEAttribute()
    {
        // Get the average of the e1, e2, e3 values, not counting the null values
        $es = array_filter([$this->e1, $this->e2, $this->e3]);
        return count($es) > 0 ? array_sum($es) / count($es) : null;
    }

    public function getEScoreAttribute()
    {
        if ($this->e == 0 || $this->d == 0) {
            return 0;
        }
        return 10 - $this->e;
    }
}
