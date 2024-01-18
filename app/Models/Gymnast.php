<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Gymnast extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'name',
        'birthdate',
        'photo'
    ];

    public function getFirstNameAttribute()
    {
        return explode(' ', $this->name)[0];
    }

    public function getLastNameAttribute()
    {
        // Explode return everything after the first space
        $lastName = explode(' ', $this->name);
        array_shift($lastName);
        return implode(' ', $lastName);
    }
}
