<?php

namespace App\Providers;

use App\Models\Score;
use App\Models\Setting;
use App\Models\MatchDay;
use App\Models\Wedstrijd;
use App\Models\Competition;
use App\Models\ProcessedScore;
use App\Models\Registration;
use App\Models\SyncTask;
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
        $models = [Competition::class, MatchDay::class, Wedstrijd::class, Registration::class, Score::class];
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
    }
}
