<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Score extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'match_day_id',
        'startnumber',
        'toestel',
        'd',
        'e',
        'n',
        'total'
    ];
}
