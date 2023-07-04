<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HubstaffService
{
    protected $token;
    protected $baseUrl;
    protected $headers;

    public function __construct()
    {
        $this->token = env("HUBSTAFF_API_TOKEN");
        $this->baseUrl = env("HUBSTAFF_API_URL");
        $this->setHeaders();
    }

    protected function setHeaders()
    {
        $this->headers = [
            "Authorization" => "Bearer {$this->token}",
            // "Content-Type" => "application/json",
            "Accept" => "application/json",
        ];
    }
    public function fetchTime()
    {
        $result = ["success" => false, "body" => []];

        $start_date = date("Y-m-d", strtotime("last monday"));
        $end_date = date("Y-m-d");

        $url = "{$this->baseUrl}/v2/organizations/519709/activities/daily?date[start]={$start_date}&date[stop]={$end_date}";
        // $url = "{$this->baseUrl}/v2/organizations/519709/activities/daily?date[start]=2023-07-04T19:50:36+00:00&date[stop]=2023-07-04T19:50:36+00:00";

        try {
            $response = Http::withHeaders($this->headers)->get($url);
            $result = [
                "success" => $response->ok(),
                "body" => $response->json(),
            ];
        } catch (\Throwable $th) {
            $result["error"] = $th->getMessage();
        }

        $data = [
            "user_id" => $response["daily_activities"][0]["user_id"],
            "time" => $response["daily_activities"][0]["tracked"],
        ];

        \Log::info("Hubstaff->fetch_users->result", $data);

        return $response->json();
    }

    public function fetch_weekly_time($user_id)
    {
        $start_date = date("Y-m-d", strtotime("last monday"));
        $end_date = date("Y-m-d");

        $response = Http::withHeaders([
            "Authorization" => "Bearer {$this->token}",
            "Content-Type" => "application/json",
        ])->get(
            $this->baseUrl .
                "weekly_team_report/{$user_id}?start_date={$start_date}&end_date={$end_date}"
        );

        return $response->json();
    }

    public function get_report()
    {
        $time = $this->fetchTime();
        $report = [];

        // foreach ($users as $user) {
        //     $time_data = $this->fetch_weekly_time($user["id"]);
        //     $total_seconds = $time_data["total_seconds"];
        //     $report[] = "User: {$user["name"]}, Time: {$total_seconds} seconds";
        // }

        return $report;
    }
}
