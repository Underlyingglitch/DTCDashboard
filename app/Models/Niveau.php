<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Niveau extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = "niveaus";
    protected $fillable = ['id', 'name', 'supplement'];

    public function getFullNameAttribute()
    {
        return $this->name . " " . $this->supplement;
    }
}
