<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Group extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function getNameAttribute()
    {
        return "Groep " . $this->nr;
    }

    public function getFullNameAttribute()
    {
        return "Baan " . $this->baan . " - Groep " . $this->nr;
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class)->orderBy('startnumber');
    }
}
