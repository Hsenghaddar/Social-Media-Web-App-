<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel("notifications.{id}",function ($user, $id) {//$user => the currently authenticated user $id => the {id} from the channel name 
    return (int) $user->id ===(int) $id;//This line protects your notifications by making sure only the correct user can listen to their private channel.
});