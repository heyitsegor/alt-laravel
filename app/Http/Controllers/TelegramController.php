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
        $callbackData = null;
        $keyboard = [
            "inline_keyboard" => [
                [
                    [
                        "text" => "Полный отчет",
                        "callback_data" => "get_full_report",
                    ],
                ],
                [
                    [
                        "text" => "Отчет по каждому проекту",
                        "callback_data" => "get_report_by_project",
                    ],
                ],
            ],
        ];

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

        if (isset($request->callback_query["data"])) {
            $callbackData = $request->callback_query["data"];
            \Log::info("callback: {$callbackData}");
        }

        if ($callbackData == "get_full_report") {
            $hubstaffService = new HubstaffService();

            $users = json_decode($hubstaffService->getFullReport(), true);
            $text = "";

            foreach ($users as $user) {
                $name = $user["name"];
                $totalHours = $user["totalHours"];

                $totalSeconds = $totalHours;
                $totalDays = floor($totalSeconds / (24 * 60 * 60));
                $remainingSeconds = $totalSeconds % (24 * 60 * 60);
                $timeString =
                    $totalDays . " дней, " . gmdate("H:i", $remainingSeconds);

                $text .=
                    "Общее время пользователя " .
                    $name .
                    " за текущую неделю составило: " .
                    $timeString .
                    "\n\n";
            }
        } elseif ($callbackData == "get_report_by_project") {
            $hubstaffService = new HubstaffService();

            $projects = json_decode(
                $hubstaffService->getReportByProject(),
                true
            );

            \Log::info("projects", ["body" => $projects]);
            $text = "";

            foreach ($projects as $project) {
                $projectTitle = $project["title"];
                $totalHours = $project["totalHours"];

                $totalSeconds = $totalHours;
                $totalDays = floor($totalSeconds / (24 * 60 * 60));
                $remainingSeconds = $totalSeconds % (24 * 60 * 60);
                $timeString =
                    $totalDays . " дней, " . gmdate("H:i", $remainingSeconds);

                $text .=
                    "Время работы над проектом " .
                    $projectTitle .
                    " составляет: " .
                    $timeString .
                    ".\n\n";
            }
        } else {
            if (!cache()->has("chat_id_{$chatId}")) {
                $text = "Welcome to alt-laravel-bot";

                cache()->put("chat_id_{$chatId}", true, now()->addMinute());
            } else {
                $text = "Press the button below for information:";
            }
        }

        $data = [
            "chat_id" => $chatId,
            "text" => $text,
            "reply_markup" => json_encode($keyboard),
        ];

        $result = app("telegram_bot")->sendMessage($data);

        return response()->json($result, 200);
    }
}
