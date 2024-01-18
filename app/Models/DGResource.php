<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DGResource extends Model
{
    use HasFactory;

    protected $table = 'dg_resources';

    protected $fillable = [
        'category',
        'name',
        'type',
        'url',
        'old_hash',
        'status',
    ];
}
