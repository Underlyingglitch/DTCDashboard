<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('livescores', function ($user) {
    return [$user->id];
});

Broadcast::channel('jurytafel.{toestel}', function ($user, $toestel) {
    if (!$user->can('jurytafel')) return false;
    return [$user->id];
});

Broadcast::channel('monitor', function ($user) {
    Log::info('monitor channel device: ');
    return [$user->id];
});
