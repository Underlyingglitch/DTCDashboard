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
        'e',
        'n',
        'total',
        'approved',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($scoreCorrection) {

            event(new \App\Events\Jury\ScoreCorrectionAdded($scoreCorrection));
        });

        static::updated(function ($scoreCorrection) {
            event(new \App\Events\Jury\ScoreCorrectionUpdated($scoreCorrection, 'update'));
        });

        static::deleted(function ($scoreCorrection) {
            event(new \App\Events\Jury\ScoreCorrectionUpdated($scoreCorrection, 'delete'));
        });
    }

    public function score()
    {
        return $this->belongsTo(Score::class);
    }

    public function getEScoreAttribute()
    {
        if ($this->e == 0 || $this->d == 0) {
            return 0;
        }
        return 10 - $this->e;
    }

    public function approve()
    {
        $this->approved = true;
        $score = Score::find($this->score_id);
        //TODO - remove score on DNS only
        if ($this->d == 0) {
            ScoreCorrection::withTrashed()->where('score_id', $score->id)->forceDelete();
            $score->delete();
        } else {
            $score->update([
                'd' => $this->d,
                'e1' => $this->e1,
                'e2' => $this->e2,
                'e3' => $this->e3,
                'n' => $this->n,
                'total' => $this->total
            ]);
        }

        $this->save();
    }
}
