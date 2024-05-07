<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'key', 'type', 'value'];
    private static $default_types = [
        'dg_resources_subscribed' => 'boolean',
        'calendar_updates_enabled_new' => 'boolean',
        'calendar_updates_enabled_change' => 'boolean',
        'calendar_updates_new_districts' => 'array',
        'calendar_updates_new_disciplines' => 'array',
        'calendar_updates_change_districts' => 'array',
        'calendar_updates_change_disciplines' => 'array',
    ];

    protected static function booted()
    {
        static::addGlobalScope('user_id', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) return $default;
        if ($setting->type == 'boolean') return $setting->value == 'true';
        if ($setting->type == 'integer') return (int)$setting->value;
        if ($setting->type == 'array') return json_decode($setting->value, true);
        return $setting->value;
    }

    public static function setValue($key, $value)
    {
        // If setting does not exist, create it
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            $setting = self::create([
                'key' => $key,
                'user_id' => auth()->id(),
                'type' => self::$default_types[$key] ?? 'string'
            ]);
        }
        // Set value
        if ($setting->type == 'boolean') $value = $value ? 'true' : 'false';
        if ($setting->type == 'array') $value = json_encode($value);
        $setting->value = $value;
        self::updateOrCreate(
            ['key' => $key, 'user_id' => auth()->id()],
            ['value' => $value]
        );
    }
}
