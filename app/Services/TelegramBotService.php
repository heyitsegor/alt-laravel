<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramBotService
{
    protected $token;
    protected $apiEndpoint;
    protected $headers;

    public function __construct()
    {
        $this->token = env("TELEGRAM_API_TOKEN");
        $this->apiEndpoint = env("TELEGRAM_API_URL");
        $this->setHeaders();
    }

    protected function setHeaders()
    {
        $this->headers = [
            "Content-Type" => "application/json",
            "Accept" => "application/json",
        ];
    }

    public function sendMessage($data)
    {
        $result = ["success" => false, "body" => []];

        $url = "{$this->apiEndpoint}/{$this->token}/sendMessage";

        try {
            $response = Http::withHeaders($this->headers)->post($url, $data);
            $result = [
                "success" => $response->ok(),
                "body" => $response->json(),
            ];
        } catch (\Throwable $th) {
            $result["error"] = $th->getMessage();
        }

        \Log::info("TelegramBot->sendMessage->result", ["result" => $result]);

        return $result;
    }
}
