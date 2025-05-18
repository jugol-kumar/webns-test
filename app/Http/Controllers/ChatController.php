<?php

namespace App\Http\Controllers;

use App\Service\ChatService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

class ChatController extends Controller
{
    use ApiResponse;
    public ChatService $chatService;
    public function __construct()
    {
        $this->chatService = new ChatService();
    }

    public function getUsers(){
        $users = $this->chatService->getUsers();
        if(!empty($users)){
            return self::success($users);
        }
        return self::error();
    }

    public function sendMessage(Request $request){
        $message = $this->chatService->sendMessage($request->all());
        if(!empty($message)){
            return self::success($message);
        }
        return self::error();
    }


}
