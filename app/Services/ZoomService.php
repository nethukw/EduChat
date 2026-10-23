<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    /**
     * Get Zoom OAuth Access Token (Server-to-Server) 
     */
    protected function getAccessToken()
    {
        try {
            $clientId = env('ZOOM_CLIENT_ID');
            $clientSecret = env('ZOOM_CLIENT_SECRET');
            $accountId = env('ZOOM_ACCOUNT_ID');

            // Call the Zoom OAuth API
            $response = Http::withoutVerifying() // Skip SSL verification for local dev
                ->asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post("https://zoom.us/oauth/token", [
                    'grant_type' => 'account_credentials',
                    'account_id' => $accountId,
                ]);

            if ($response->failed()) {
                Log::error('Zoom Auth Failed: ' . $response->body());
                return null;
            }

            return $response->json()['access_token'];

        } catch (\Exception $e) {
            Log::error('Zoom Auth Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Automatically create a Zoom meeting (Class)
     */
    public function createMeeting($title, $startTime, $duration)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            Log::error('Zoom Meeting Creation aborted: No Access Token.');
            return null;
        }

        try {
            // Call the Zoom API to create a class meeting
            $response = Http::withoutVerifying() 
                ->withToken($token)
                ->post('https://api.zoom.us/v2/users/me/meetings', [
                    'topic'      => $title,
                    'type'       => 2, // Scheduled meeting
                    'start_time' => date('Y-m-d\TH:i:s', strtotime($startTime)),
                    'duration'   => $duration,
                    'timezone'   => 'Asia/Colombo',
                    'settings'   => [
                        'join_before_host' => true,
                        'host_video'       => true,
                        'participant_video' => true,
                        'mute_upon_entry'  => true,
                        'waiting_room'     => false, // To allow joining directly without a waiting room
                        'approval_type'    => 0,     // Automatically approve
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Zoom Meeting API Failed: ' . $response->body());
                return null;
            }

            // If successful, return the join_url (to be saved in the database)
            return $response->json()['join_url'];

        } catch (\Exception $e) {
            Log::error('Zoom Meeting Creation Exception: ' . $e->getMessage());
            return null;
        }
    }
}