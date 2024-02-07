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
        'e',
        'n',
        'total',
        'counted'
    ];

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
        return 10 - $this->e;
    }
}
