<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Team extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    public $fillable = ['name', 'competition_id', 'niveau_id'];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class)->orderBy('startnumber');
    }
}
