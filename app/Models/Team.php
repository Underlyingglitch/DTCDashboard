<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

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
