<?php

namespace App\Providers;

use App\Models\Club;
use App\Models\Team;
use App\Models\Score;
use App\Models\Gymnast;
use App\Models\Setting;
use App\Models\MatchDay;
use App\Models\SyncTask;
use App\Models\Wedstrijd;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\ProcessedScore;
use App\Models\ScoreCorrection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class DatabaseSyncServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // If server is not production, do nothing
        if (config('app.env') != 'local') {
            return;
        }
        $models = [Competition::class, MatchDay::class, Wedstrijd::class, Registration::class, Gymnast::class, Club::class, Score::class, ScoreCorrection::class, Team::class];
        foreach ($models as $model) {
            $model::creating(function ($model) {
                if (request()->is('api/*')) {
                    return true;
                }
                SyncTask::create([
                    'model_type' => get_class($model),
                    'model_id' => $model->id,
                    'operation' => 'create',
                    'data' => json_encode($model->toArray()),
                ]);
            });
            $model::updating(function ($model) {
                if (request()->is('api/*')) {
                    return true;
                }
                SyncTask::create([
                    'model_type' => get_class($model),
                    'model_id' => $model->id,
                    'operation' => 'update',
                    'data' => json_encode($model->getDirty()),
                ]);
            });
        }
        Setting::creating(function ($setting) {
            if (request()->is('api/*')) {
                return true;
            }
            SyncTask::create([
                'model_type' => get_class($setting),
                'model_id' => $setting->id,
                'operation' => 'setting',
                'data' => json_encode([$setting->key, $setting->value]),
            ]);
        });
        Setting::updating(function ($setting) {
            if (request()->is('api/*')) {
                return true;
            }
            SyncTask::create([
                'model_type' => get_class($setting),
                'model_id' => $setting->id,
                'operation' => 'setting',
                'data' => json_encode([$setting->key, $setting->value]),
            ]);
        });
    }
}
