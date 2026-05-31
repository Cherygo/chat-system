<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('chat.{chatId}', function (User $user, $chatId) {
    $chat = Chat::find($chatId);
    if(!$chat) {
        return false;
    }

    return $chat->users->contains($user->id);
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
