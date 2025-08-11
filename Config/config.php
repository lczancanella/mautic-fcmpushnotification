<?php
// plugins/FcmPushNotificationBundle/Config/config.php

return [
    'name'        => 'FCM Push Notification',
    'description' => 'Enables sending push notifications via Firebase Cloud Messaging in campaigns',
    'version'     => '1.0.0',
    'author'      => 'Your Name',
    'services'    => [
        'events' => [
            'mautic.fcm_push_notification.campaign.subscriber' => [
                'class'     => \MauticPlugin\FcmPushNotificationBundle\EventListener\CampaignSubscriber::class,
                'arguments' => [
                    'mautic.fcm_push_notification.helper',
                ],
            ],
        ],
        'forms' => [
            'mautic.fcm_push_notification.form.type.action' => [
                'class'     => \MauticPlugin\FcmPushNotificationBundle\Form\Type\FcmPushActionType::class,
                'arguments' => [],
            ],
        ],
        'others' => [
            'mautic.fcm_push_notification.helper' => [
                'class'     => \MauticPlugin\FcmPushNotificationBundle\Helper\FcmHelper::class,
                'arguments' => [
                    'monolog.logger.mautic',
                ],
            ],
        ],
    ],
    'routes' => [],
];