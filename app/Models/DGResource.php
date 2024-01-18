<?php

namespace App\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getUrlAttribute()
    {
        if ($this->status == 'deleted') {
            if (Storage::exists('dg_resources/' . $this->id . '.pdf')) {
                return URL::signedRoute('dg_resources.download', ['dg_resource' => $this->id]);
            }
            return null;
        } else {
            return $this->attributes['url'];
        }
    }
}
