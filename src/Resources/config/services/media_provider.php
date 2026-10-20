<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.media_provider.image', \BitBag\SyliusCmsPlugin\MediaProvider\GenericProvider::class)
        ->args([
            service('bitbag_sylius_cms_plugin.media_uploader'),
            service('twig'),
            '@BitBagSyliusCmsPlugin/Shop/Media/Show/image.html.twig',
            'media/image',
        ])
        ->tag('bitbag_sylius_cms_plugin.media_provider', ['type' => 'image', 'label' => 'bitbag_sylius_cms_plugin.ui.image_provider']);

    $services->set('bitbag_sylius_cms_plugin.media_provider.video', \BitBag\SyliusCmsPlugin\MediaProvider\GenericProvider::class)
        ->args([
            service('bitbag_sylius_cms_plugin.media_uploader'),
            service('twig'),
            '@BitBagSyliusCmsPlugin/Shop/Media/Show/video.html.twig',
            'media/video',
        ])
        ->tag('bitbag_sylius_cms_plugin.media_provider', ['type' => 'video', 'label' => 'bitbag_sylius_cms_plugin.ui.video_provider']);

    $services->set('bitbag_sylius_cms_plugin.media_provider.file', \BitBag\SyliusCmsPlugin\MediaProvider\GenericProvider::class)
        ->args([
            service('bitbag_sylius_cms_plugin.media_uploader'),
            service('twig'),
            '@BitBagSyliusCmsPlugin/Shop/Media/Show/file.html.twig',
            'media/file',
        ])
        ->tag('bitbag_sylius_cms_plugin.media_provider', ['type' => 'file', 'label' => 'bitbag_sylius_cms_plugin.ui.file_provider']);
};
