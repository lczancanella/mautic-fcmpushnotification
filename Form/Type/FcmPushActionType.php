<?php
// plugins/FcmPushNotificationBundle/Form/Type/FcmPushActionType.php

declare(strict_types=1);

namespace MauticPlugin\FcmPushNotificationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FcmPushActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class, [
            'label' => 'Notification Title',
            'attr'  => ['class' => 'form-control'],
        ]);

        $builder->add('body', TextareaType::class, [
            'label' => 'Notification Body',
            'attr'  => ['class' => 'form-control'],
        ]);

        $builder->add('bearer_token', TextType::class, [
            'label' => 'Firebase Bearer Token',
            'attr'  => ['class' => 'form-control'],
        ]);
    }
}