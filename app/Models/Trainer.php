<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Trainer extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'club_id',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
