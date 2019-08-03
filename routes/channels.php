<?php

use App\Models\Trade;
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

Broadcast::channel('user.{id}', function ($user, $id){
    return $user->id == $id || $user->can('view user details');
});

Broadcast::channel('user.{id}.presence', function ($user, $id){
    return true;
});

Broadcast::channel('trade.{token}', function ($user, $token){
    return Trade::where('token', $token)->first()->grantAccess($user);
});

Broadcast::channel('administration', function ($user){
    return $user->can('access admin panel');
});
