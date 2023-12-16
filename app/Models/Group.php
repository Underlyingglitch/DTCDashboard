<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

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
