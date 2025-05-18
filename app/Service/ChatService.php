<?php

namespace App\Service;

use App\Events\ChatEvent;
use App\Models\User;

class ChatService
{
    public function getUsers()
    {
        $users = User::where('id', '!=', auth()->id())->get();

        if(!empty($users)){
            return $users;
        }

        return false;
    }

    public function sendMessage($message){
        // save here cheating data on database if you need to store for save for latter
        $event = new ChatEvent($message);
        $event->dontBroadcastToCurrentUser();
        event($event);
        return true;
    }


}
