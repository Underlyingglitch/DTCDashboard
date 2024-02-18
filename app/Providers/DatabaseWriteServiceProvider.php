<?php

namespace App\Providers;

use App\Models\Score;
use App\Models\Setting;
use App\Models\MatchDay;
use App\Models\Wedstrijd;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\PendingChange;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class DatabaseWriteServiceProvider extends ServiceProvider
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
        if (config('app.env') != 'production' && config('app.env') != 'dev') {
            return;
        }
        // If database write is enabled, do nothing
        if (Setting::getValue('db_write') == 'on') {
            return;
        }
        $models = [Competition::class, MatchDay::class, Wedstrijd::class, Registration::class, Score::class];
        foreach ($models as $model) {
            $model::creating(function ($model) {
                if (request()->is('api/*')) {
                    return true;
                }
                PendingChange::create([
                    'model_type' => get_class($model),
                    'model_id' => $model->id,
                    'operation' => 'create',
                    'data' => json_encode($model->toArray()),
                ]);
                return false; // Cancel the operation
            });
            $model::updating(function ($model) {
                if (request()->is('api/*')) {
                    return true;
                }
                PendingChange::create([
                    'model_type' => get_class($model),
                    'model_id' => $model->id,
                    'operation' => 'update',
                    'data' => json_encode($model->getDirty()),
                ]);
                return false; // Cancel the operation
            });
        }
    }
}
