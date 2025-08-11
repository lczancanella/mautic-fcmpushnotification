<?php
// plugins/FcmPushNotificationBundle/Helper/FcmHelper.php

declare(strict_types=1);

namespace MauticPlugin\FcmPushNotificationBundle\Helper;

use Psr\Log\LoggerInterface;

class FcmHelper
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sendPushNotification(string $fcmToken, string $title, string $body, string $bearerToken): bool
    {
        $url = 'https://fcm.googleapis.com/v1/projects/your-project-id/messages:send';

        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $bearerToken,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $this->logger->info('FCM Push Notification sent successfully to token: ' . $fcmToken);
            return true;
        } else {
            $this->logger->error('FCM Push Notification failed: ' . $response);
            return false;
        }
    }
}