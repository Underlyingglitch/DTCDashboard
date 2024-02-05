<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'key', 'value'];

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
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value)
    {
        self::updateOrCreate(
            ['key' => $key, 'user_id' => auth()->id()],
            ['value' => $value]
        );
    }
}
