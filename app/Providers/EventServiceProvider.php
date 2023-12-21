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
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(function (QueueBusy $event) {
            Notification::route('mail', 'dev@example.com')
                ->notify(new EmailNotification(
                    "rickokkersen@gmail.com",
                    "Queue is busy",
                    "The current queue is backed up with " . $event->size . " jobs"
                ));
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
