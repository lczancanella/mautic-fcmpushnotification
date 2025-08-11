<?php
// plugins/FcmPushNotificationBundle/EventListener/CampaignSubscriber.php

declare(strict_types=1);

namespace MauticPlugin\FcmPushNotificationBundle\EventListener;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use MauticPlugin\FcmPushNotificationBundle\Helper\FcmHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CampaignSubscriber implements EventSubscriberInterface
{
    private $fcmHelper;

    public function __construct(FcmHelper $fcmHelper)
    {
        $this->fcmHelper = $fcmHelper;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CampaignEvents::CAMPAIGN_ON_BUILD => ['onCampaignBuild', 0],
            'fcm.push_notification.send'      => ['onCampaignExecute', 0],
        ];
    }

    public function onCampaignBuild(CampaignBuilderEvent $event): void
    {
        $event->addAction('fcm.push_notification.send', [
            'label'           => 'Send FCM Push Notification',
            'description'     => 'Send a push notification via Firebase Cloud Messaging',
            'formType'        => \MauticPlugin\FcmPushNotificationBundle\Form\Type\FcmPushActionType::class,
            'eventName'       => 'fcm.push_notification.send',
            'formTheme'       => 'FcmPushNotificationBundle:FormTheme',
        ]);
    }

    public function onCampaignExecute(CampaignExecutionEvent $event): void
    {
        $config = $event->getConfig();
        $contact = $event->getContact();

        $fcmToken = $contact->getFieldValue('fcm_token');
        $title = $config['title'] ?? 'Default Title';
        $body = $config['body'] ?? 'Default Body';
        $bearerToken = $config['bearer_token'] ?? '';

        if (empty($fcmToken) || empty($bearerToken)) {
            $event->setResult(false);
            return;
        }

        $result = $this->fcmHelper->sendPushNotification($fcmToken, $title, $body, $bearerToken);

        $event->setResult($result);
    }
}