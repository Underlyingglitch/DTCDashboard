<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

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
