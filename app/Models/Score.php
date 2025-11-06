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
        'b',
        'n',
        'total',
        'place',
        'counted'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($score) {
            $score->total = $score->calculateTotal();
        });

        static::updating(function ($score) {
            $total = $score->calculateTotal();
            \Illuminate\Support\Facades\Log::info("total calculation for score ID {$score->id}: d={$score->d}, e={$score->e}, n={$score->n}, b={$score->b}, total={$total}");
            $score->total = $total;
        });
    }

    public function calculateTotal()
    {
        $es = array_filter([$this->e1, $this->e2, $this->e3]);
        $this->e = count($es) > 0 ? round(array_sum($es) / count($es), 3) : null;
        $total = $this->d > 0 ? (($this->d + (10 - $this->e)) - $this->n + $this->b) : 0;
        if ($total < 0) $total = 0;
        return $total;
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
