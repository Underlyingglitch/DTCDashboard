<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'model_type',
        'model_id',
        'operation',
        'data',
        'synced'
    ];
}
