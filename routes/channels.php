<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    \Illuminate\Support\Facades\Log::info("Broadcast Auth Attempt", ['user_id' => $user->id_users, 'requested_id' => $id]);
    return (int) $user->id_users === (int) $id;
});
