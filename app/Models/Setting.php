<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getValue($key)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting->value ?? null;
    }

    public static function setValue($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getValues(array $keys)
    {
        return (object)Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();
    }
}
