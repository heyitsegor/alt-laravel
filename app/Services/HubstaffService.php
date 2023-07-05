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
            "Accept" => "application/json",
        ];
    }

    protected function getOrganizationId()
    {
        $result = ["success" => false, "body" => []];

        $url = "{$this->baseUrl}/v2/organizations";

        try {
            $response = Http::withHeaders($this->headers)->get($url);

            $data = json_decode($response, true);
            \Log::info("organisations", ["result" => $data]);
            $id = $data["organizations"][0]["id"];

            $result = [
                "success" => $response->ok(),
                "body" => $id,
            ];
        } catch (\Throwable $th) {
            $result["error"] = $th->getMessage();
        }

        return $result;
    }

    protected function getActivityData($organizationId)
    {
        $result = ["success" => false, "body" => []];

        $startDate = date("Y-m-d", strtotime("last monday midnight"));
        $endDate = date("Y-m-d");

        \Log::info("organisation id:", ["id" => $organizationId]);

        $url = "{$this->baseUrl}/v2/organizations/{$organizationId}/activities/daily?date[start]={$startDate}&date[stop]={$endDate}";

        try {
            $response = Http::withHeaders($this->headers)->get($url);

            $result = [
                "success" => $response->ok(),
                "body" => $response->json(),
            ];
        } catch (\Throwable $th) {
            $result["error"] = $th->getMessage();
        }

        return $result;
    }

    protected function getUsernameById($userId)
    {
        $result = ["success" => false, "body" => []];

        $url = "{$this->baseUrl}/v2/users/{$userId}";

        try {
            $response = Http::withHeaders($this->headers)->get($url);

            $data = json_decode($response, true);
            $name = $data["user"]["name"];

            $result = [
                "success" => $response->ok(),
                "body" => $name,
            ];
        } catch (\Throwable $th) {
            $result["error"] = $th->getMessage();
        }

        return $result;
    }

    protected function getProjectTitles($organizationId)
    {
        $result = ["success" => false, "body" => []];

        $url = "{$this->baseUrl}/v2/organizations/{$organizationId}/projects";

        try {
            $response = Http::withHeaders($this->headers)->get($url);

            $data = json_decode($response, true);
        } catch (\Throwable $th) {
            $result["error"] = $th->getMessage();
        }

        $projectsTracked = [];

        foreach ($data["projects"] as $project) {
            $projectId = $project["id"];
            $projectTitle = $project["name"];

            $projectsTracked[$projectId] = $projectTitle;
        }

        $result = [
            "success" => $response->ok(),
            "body" => $projectsTracked,
        ];

        return $result;
    }

    protected function getWeeklyTime($data)
    {
        $usersTracked = [];
        foreach ($data["daily_activities"] as $activity) {
            $userId = $activity["user_id"];
            $userName = $this->getUsernameById($userId)["body"];

            $time = $activity["tracked"];

            if (!isset($usersTracked[$userName])) {
                $usersTracked[$userName] = [
                    "name" => $userName,
                    "totalHours" => 0,
                ];
            }

            $usersTracked[$userName]["totalHours"] += $time;
        }

        $result = array_values($usersTracked);

        return json_encode($result);
    }

    protected function getProjectTime($data, $titles)
    {
        $projectsTracked = [];

        foreach ($data["daily_activities"] as $activity) {
            $userId = $activity["user_id"];
            $userName = $this->getUsernameById($userId)["body"];
            $projectId = $activity["project_id"];
            $projectTitle = $titles[$projectId];

            $time = $activity["tracked"];

            if (!isset($projectsTracked[$projectTitle])) {
                $projectsTracked[$projectTitle] = [
                    "user" => $userName,
                    "title" => $projectTitle,
                    "totalHours" => 0,
                ];
            }

            $projectsTracked[$projectTitle]["totalHours"] += $time;
        }

        $result = array_values($projectsTracked);

        return json_encode($result);
    }

    public function getFullReport()
    {
        $organizationId = $this->getOrganizationId();
        $activityData = $this->getActivityData($organizationId["body"]);
        return $this->getWeeklyTime($activityData["body"]);
    }

    public function getReportByProject()
    {
        $organizationId = $this->getOrganizationId();
        $activityData = $this->getActivityData($organizationId["body"]);
        $titleData = $this->getProjectTitles($organizationId["body"]);
        return $this->getProjectTime($activityData["body"], $titleData["body"]);
    }
}
