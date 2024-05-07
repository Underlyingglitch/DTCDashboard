<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'calendar_item_id',
        'type',
        'value'
    ];

    public function calendar_item()
    {
        return $this->belongsTo(CalendarItem::class);
    }
}
