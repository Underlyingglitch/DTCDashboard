<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Jury extends Model implements Auditable
{
    use SoftDeletes, CascadeSoftDeletes, \OwenIt\Auditing\Auditable;

    protected $cascadeDeletes = ['declarations'];

    protected $fillable = [
        'name',
        'email',
        'function',
        'postal',
        'city',
        'iban',
        'club_id',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function declarations()
    {
        return $this->hasMany(Declaration::class);
    }
}
