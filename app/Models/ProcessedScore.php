<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessedScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedstrijd_id',
        'group_id',
        'toestel',
        'completed',
    ];
}
