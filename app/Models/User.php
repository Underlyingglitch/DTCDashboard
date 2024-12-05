<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\EmailVerification;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable, MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes, \OwenIt\Auditing\Auditable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'locked',
        'email_verified_at',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $auditExclude = [
        'last_seen_at',
    ];

    public function settings()
    {
        return $this->hasMany(UserSetting::class);
    }

    public function getIsTrainerAttribute()
    {
        return $this->trainers()->count() > 0;
    }

    public function trainers()
    {
        return $this->hasMany(Trainer::class, 'email', 'email');
    }

    public function getIsJuryAttribute()
    {
        if (preg_match('/^jury[0-9]@dtc\.local$/', $this->email)) return true;
        return !is_null($this->jury);
    }

    public function jury()
    {
        return $this->hasOne(Jury::class, 'email', 'email');
    }

    public function clubs()
    {
        if ($this->is_trainer) {
            return $this->hasManyThrough(Club::class, Trainer::class, 'email', 'id', 'email', 'club_id');
        }
        return null;
    }

    public function sendEmailVerificationNotification()
    {
        $minutes = Config::get('auth.verification.expire', 60);
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes($minutes),
            [
                'id' => $this->getKey(),
                // deepcode ignore InsecureHash: Just a simple hash, no need for a secure one
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        $this->notify(new EmailVerification($verifyUrl, $minutes));
    }

    public function calendar_subscriptions()
    {
        return $this->belongsToMany(CalendarItem::class);
    }
}
