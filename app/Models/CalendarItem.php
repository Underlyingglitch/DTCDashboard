<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'discipline',
        'district',
        'place',
        'date_from',
        'date_to',
        'results',
        'results_files',
        'program',
        'program_files',
        'description',
        'description_files'
    ];

    protected $casts = [
        'date_from' => 'datetime',
        'date_to' => 'datetime',
        'results_files' => 'array',
        'program_files' => 'array',
        'description_files' => 'array'
    ];

    protected $appends = [
        'date'
    ];

    public function getDateAttribute()
    {
        return $this->parseDate($this->date_from, $this->date_to);
    }

    public static function parseDate($from, $to)
    {
        $from = Carbon::parse($from);
        $to = $to ? Carbon::parse($to) : null;
        if (!$to || $from->isSameDay($to)) {
            return $from->locale('nl')->isoFormat('D MMM YYYY');
        } elseif ($from->isSameMonth($to)) {
            return $from->locale('nl')->isoFormat('D') . ' t/m ' . $to->locale('nl')->isoFormat('D MMM YYYY');
        } elseif ($from->isSameYear($to)) {
            return $from->locale('nl')->isoFormat('D MMM') . ' t/m ' . $to->locale('nl')->isoFormat('D MMM YYYY');
        } else {
            return $from->locale('nl')->isoFormat('D MMM YYYY') . ' t/m ' . $to->locale('nl')->isoFormat('D MMM YYYY');
        }
    }

    public function calendar_updates()
    {
        return $this->hasMany(CalendarUpdate::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class);
    }
}
