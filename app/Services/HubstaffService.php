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

    protected function sendRequest(
        string $method,
        string $url,
        array $formData = null
    ) {
        try {
            $response = Http::withHeaders($this->headers)->$method(
                $this->baseUrl . $url,
                $formData
            );
            return $response->json();
        } catch (\Throwable $th) {
            return ["error" => $th->getMessage()];
        }
    }

    protected function getOrganizationId()
    {
        $data = $this->sendRequest("get", "/v2/organizations");
        return $data["organizations"][0]["id"];
    }

    protected function getActivityData($organizationId)
    {
        $startDate = date("Y-m-d", strtotime("last monday midnight"));
        $endDate = date("Y-m-d");
        $url = "/v2/organizations/{$organizationId}/activities/daily?date[start]={$startDate}&date[stop]={$endDate}";

        return $this->sendRequest("get", $url);
    }

    protected function getUsernameById($userId)
    {
        $data = $this->sendRequest("get", "/v2/users/{$userId}");
        return $data["user"]["name"];
    }

    protected function getProjectTitles($organizationId)
    {
        $data = $this->sendRequest(
            "get",
            "/v2/organizations/{$organizationId}/projects"
        );
        $projectTitles = array_column($data["projects"], "name", "id");

        return $projectTitles;
    }

    protected function getWeeklyTime($data)
    {
        $usersTracked = [];
        foreach ($data["daily_activities"] as $activity) {
            $userId = $activity["user_id"];
            $userName = $this->getUsernameById($userId);

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
            $projectId = $activity["project_id"];
            $projectTitle = $titles[$projectId];

            $time = $activity["tracked"];

            if (!isset($projectsTracked[$projectTitle])) {
                $projectsTracked[$projectTitle] = [
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
        $activityData = $this->getActivityData($organizationId);
        return $this->getWeeklyTime($activityData);
    }

    public function getReportByProject()
    {
        $organizationId = $this->getOrganizationId();
        $activityData = $this->getActivityData($organizationId);
        $titleData = $this->getProjectTitles($organizationId);
        return $this->getProjectTime($activityData, $titleData);
    }
}
