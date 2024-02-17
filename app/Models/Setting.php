<?php

namespace App\Models;

use App\Events\SettingUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $table = "user_settings";

    protected $fillable = [
        'key',
        'value',
    ];

    // Set a global scope to only include records where user_id = null
    protected static function booted()
    {
        static::addGlobalScope('user_id', function ($query) {
            $query->where('user_id', null);
        });
    }

    public static function getValue(string $key)
    {
        $value = Cache::get($key);
        if ($value === null) {
            $value = Setting::where('key', $key)->first()->value ?? null;
        }
        Cache::put($key, $value, 60 * 60 * 24 * 7);
        return $value;
    }

    public static function setValue(string $key, mixed $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        if ($key == 'current_wedstrijd') {
            $wedstrijd = Wedstrijd::find($value);
            Setting::updateOrCreate(
                ['key' => 'current_competition'],
                ['value' => $wedstrijd->match_day->competition_id]
            );
            Cache::put('current_competition', $wedstrijd->match_day->competition_id, 60 * 60 * 24 * 7);
            Setting::updateOrCreate(
                ['key' => 'current_match_day'],
                ['value' => $wedstrijd->match_day_id]
            );
            Cache::put('current_match_day', $wedstrijd->match_day_id, 60 * 60 * 24 * 7);
            Setting::updateOrCreate(
                ['key' => 'current_round'],
                ['value' => 1]
            );
            Cache::put('current_round', 1, 60 * 60 * 24 * 7);
        }
        Cache::put($key, $value, 60 * 60 * 24 * 7);
        event(new SettingUpdated($key, $value));
    }

    public static function getValues(array $keys)
    {
        return (object)Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();
    }
}
