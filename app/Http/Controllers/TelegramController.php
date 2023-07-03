<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function inbound(Request $request)
    {
        \Log::info($request->all());

        $chatId = $request->message["from"]["id"];
        $replyToMessage = $request->message["message_id"];

        if (!cache()->has("chat_id_{$chatId}")) {
            $text = "Welcome to alt-laravel-bot ";

            cache()->put("chat_id_{$chatId}", true, now()->addMinute(60));
        } else {
            $text = "Hi again!";
        }

        $result = app('telegram_bot')->sendMessage($text, $chatId, $replyToMessage);
        return response()-> json($result, 200)
    }
}
