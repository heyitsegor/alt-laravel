<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HubstaffService;

class TelegramController extends Controller
{
    public function inbound(Request $request)
    {
        \Log::info($request->all());

        $chatId = null;
        if (isset($request->message) && isset($request->message["from"])) {
            $chatId = $request->message["from"]["id"];
        }
        if (
            !$chatId &&
            isset($request->callback_query) &&
            isset($request->callback_query["from"])
        ) {
            $chatId = $request->callback_query["from"]["id"];
        }

        \Log::info("chat_id: {$chatId}");

        if (isset($request->message["message_id"])) {
            $replyToMessageId = $request->message["message_id"];
            \Log::info("reply_to_message: {$replyToMessageId}");
        }

        $callbackData = null;

        if (isset($request->callback_query["data"])) {
            $callbackData = $request->callback_query["data"];
            \Log::info("callback: {$callbackData}");
        }

        if ($callbackData == "get_report") {
            $hubstaffService = new HubstaffService();
            $report = $hubstaffService->get_report();

            $data = [
                "chat_id" => $chatId,
                "text" => implode("\n", $report),
            ];

            $result = app("telegram_bot")->sendMessage($data);

            return response()->json($result, 200);
        }

        if (!cache()->has("chat_id_{$chatId}")) {
            $text = "Welcome to alt-laravel-bot";

            cache()->put("chat_id_{$chatId}", true, now()->addMinute());
        } else {
            // $text = "Hi again!";
            $text = "Press the button below for information:";
        }

        $keyboard = [
            "inline_keyboard" => [
                [["text" => "Info", "callback_data" => "info"]],
                [["text" => "Get Report", "callback_data" => "get_report"]],
            ],
        ];

        $data = [
            "chat_id" => $chatId,
            "reply_to_message_id" => $replyToMessageId,
            "text" => $text,
            "reply_markup" => json_encode($keyboard),
        ];

        $result = app("telegram_bot")->sendMessage($data);

        return response()->json($result, 200);
    }
}
