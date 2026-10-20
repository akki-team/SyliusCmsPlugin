<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.menu.content_management', \BitBag\SyliusCmsPlugin\Menu\ContentManagementMenuBuilder::class)
        ->tag('kernel.event_listener', ['event' => 'sylius.menu.admin.main', 'method' => 'buildMenu']);

    $services->set('bitbag_sylius_cms_plugin.event_listener.media_upload', \BitBag\SyliusCmsPlugin\EventListener\MediaUploadListener::class)
        ->args([
            service('bitbag_sylius_cms_plugin.resolver.media_provider'),
        ])
        ->tag('kernel.event_listener', ['event' => 'bitbag_sylius_cms_plugin.media.pre_create', 'method' => 'uploadMedia'])
        ->tag('kernel.event_listener', ['event' => 'bitbag_sylius_cms_plugin.media.pre_update', 'method' => 'uploadMedia']);

    $services->set('bitbag_sylius.event_subscriber.resource_delete', \BitBag\SyliusCmsPlugin\EventListener\ResourceDeleteSubscriber::class)
        ->args([
            service('router'),
            service('request_stack'),
        ])
        ->tag('kernel.event_subscriber', ['event' => 'kernel.exception']);
};
