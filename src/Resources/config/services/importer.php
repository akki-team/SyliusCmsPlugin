<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.importer.chain', \BitBag\SyliusCmsPlugin\Importer\ImporterChain::class);

    $services->set('bitbag_sylius_cms_plugin.importer.page', \BitBag\SyliusCmsPlugin\Importer\PageImporter::class)
        ->args([
            service('bitbag_sylius_cms_plugin.resolver.resource.page'),
            service('sylius.context.locale'),
            service('bitbag_sylius_cms_plugin.downloader.image'),
            service('bitbag_sylius_cms_plugin.factory.media'),
            service('bitbag_sylius_cms_plugin.resolver.media_provider'),
            service('bitbag_sylius_cms_plugin.resolver.importer_sections'),
            service('bitbag_sylius_cms_plugin.resolver.importer_channels'),
            service('bitbag_sylius_cms_plugin.resolver.importer_products'),
            service('validator'),
            service('doctrine.orm.entity_manager'),
        ])
        ->tag('bitbag.cmsplugin.importer');

    $services->set('bitbag_sylius_cms_plugin.importer.block', \BitBag\SyliusCmsPlugin\Importer\BlockImporter::class)
        ->args([
            service('bitbag_sylius_cms_plugin.resolver.resource.block'),
            service('sylius.context.locale'),
            service('bitbag_sylius_cms_plugin.resolver.importer_sections'),
            service('bitbag_sylius_cms_plugin.resolver.importer_channels'),
            service('bitbag_sylius_cms_plugin.resolver.importer_products'),
            service('validator'),
            service('bitbag_sylius_cms_plugin.repository.block'),
        ])
        ->tag('bitbag.cmsplugin.importer');

    $services->set('bitbag_sylius_cms_plugin.importer.media', \BitBag\SyliusCmsPlugin\Importer\MediaImporter::class)
        ->args([
            service('bitbag_sylius_cms_plugin.resolver.resource.media'),
            service('sylius.context.locale'),
            service('bitbag_sylius_cms_plugin.resolver.importer_sections'),
            service('bitbag_sylius_cms_plugin.resolver.importer_products'),
            service('validator'),
            service('bitbag_sylius_cms_plugin.repository.media'),
        ])
        ->tag('bitbag.cmsplugin.importer');
};
