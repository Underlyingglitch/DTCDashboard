<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelLocalEmails
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event)
    {
        // Get the 'To' addresses
        $recipients = $event->message->getTo();
        $emails = array_map(function ($recipient) {
            return $recipient->getAddress();
        }, $recipients);

        foreach ($emails as $email) {
            // If the email ends with @dtc.local, cancel the event
            if (Str::endsWith($email, '@dtc.local')) {
                return false;
            }
        }
    }
}
