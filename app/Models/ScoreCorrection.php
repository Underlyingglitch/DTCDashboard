<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'score_id',
        'd',
        'e1',
        'e2',
        'e3',
        'n',
        'total'
    ];

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
