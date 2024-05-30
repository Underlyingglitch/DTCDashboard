<?php

namespace App\Models;

use App\Events\SettingUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Schema;

class Setting extends Model
{
    use HasFactory;

    protected $table = "user_settings";

    protected $fillable = [
        'key',
        'value',
    ];

    public static $default_types = [
        'current_competition' => 'integer',
        'current_match_day' => 'integer',
        'current_wedstrijd' => 'integer',
        'current_round' => 'integer',
        'oefenstof_last_updated' => 'datetime',
        'sync_enabled' => 'boolean',
        'db_write_enabled' => 'boolean',
        'dg_resources_last_update' => 'datetime',
        'score_correction_enabled' => 'boolean',
    ];

    // Set a global scope to only include records where user_id = null
    protected static function booted()
    {
        static::addGlobalScope('user_id', function ($query) {
            $query->where('user_id', null);
        });
    }

    public static function getValue(string $key, mixed $default = null)
    {
        $value = Cache::get($key);
        if ($value === null) {
            $value = self::getDBValue($key, $default);
        }
        Cache::put($key, $value, 60 * 60 * 24 * 7);
        return $value;
    }

    public static function getDBValue(string $key, mixed $default = null)
    {
        if (Schema::hasTable('user_settings') === false) return $default;
        $setting = self::where('key', $key)->first();
        if (!$setting) return $default;
        return self::parseValue($key, $setting->value);
    }

    public static function parseValue(string $key, mixed $value)
    {
        if (self::$default_types[$key] == 'boolean') return $value == 'true';
        if (self::$default_types[$key] == 'integer') return (int)$value;
        if (self::$default_types[$key] == 'array') return json_decode($value, true);
        if (self::$default_types[$key] == 'datetime') return Carbon::parse($value);
        return $value;
    }

    public static function setValue(string $key, mixed $value, bool $cancel_event = false)
    {
        if (self::$default_types[$key] == 'boolean') $value = $value ? 'true' : 'false';
        if (self::$default_types[$key] == 'array') $value = json_encode($value);
        if (self::$default_types[$key] == 'datetime') $value = $value->toDateTimeString();
        Cache::put($key, self::parseValue($key, $value), 60 * 60 * 24 * 7);
        Setting::updateOrCreate(
            ['key' => $key],
            ['type' => self::$default_types[$key] ?? 'string', 'value' => $value]
        );
        if ($key == 'current_wedstrijd' && $value != 0) {
            $wedstrijd = Wedstrijd::find($value);
            self::setValue('current_competition', $wedstrijd->match_day->competition_id, true);
            self::setValue('current_match_day', $wedstrijd->match_day_id, true);
            self::setValue('current_round', 1, true);
        }
        if (!$cancel_event) event(new SettingUpdated($key, $value));
    }

    public static function getValues(array $keys)
    {
        return (object)Setting::whereIn('key', $keys)->pluck('value', 'key')->map(function ($value, $key) {
            return self::parseValue($key, $value);
        })->toArray();
    }
}
