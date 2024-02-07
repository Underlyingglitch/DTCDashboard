<?php

namespace App\Providers;

use App\Mail\EmailNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\Events\QueueBusy;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $subscribe = [];

    protected $observers = [
        \App\Models\Score::class => [\App\Observers\ScoreObserver::class],
        \App\Models\ProcessedScore::class => [\App\Observers\ProcessedScoreObserver::class],
        \App\Models\User::class => [\App\Observers\UserObserver::class],
        \App\Models\SyncTask::class => [\App\Observers\SyncTasksObserver::class],
        \App\Models\TeamScore::class => [\App\Observers\TeamScoreObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
