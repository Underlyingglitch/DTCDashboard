<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Club extends Model implements Auditable
{
    use SoftDeletes, CascadeSoftDeletes, \OwenIt\Auditing\Auditable;

    protected $cascadeDeletes = ['trainers'];

    protected $fillable = ['id', 'name', 'email'];

    public function trainers()
    {
        return $this->hasMany(Trainer::class);
    }
}
