<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.resolver.resource.page', \BitBag\SyliusCmsPlugin\Resolver\ResourceResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.page'),
            service('bitbag_sylius_cms_plugin.factory.page'),
            'code',
        ]);

    $services->set('bitbag_sylius_cms_plugin.resolver.resource.section', \BitBag\SyliusCmsPlugin\Resolver\ResourceResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.section'),
            service('bitbag_sylius_cms_plugin.factory.section'),
            'code',
        ]);

    $services->set('bitbag_sylius_cms_plugin.resolver.resource.block', \BitBag\SyliusCmsPlugin\Resolver\ResourceResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.block'),
            service('bitbag_sylius_cms_plugin.factory.block'),
            'code',
        ]);

    $services->set('bitbag_sylius_cms_plugin.resolver.resource.media', \BitBag\SyliusCmsPlugin\Resolver\ResourceResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.media'),
            service('bitbag_sylius_cms_plugin.factory.media'),
            'code',
        ]);

    $services->set('bitbag_sylius_cms_plugin.resolver.block_resource', \BitBag\SyliusCmsPlugin\Resolver\BlockResourceResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.block'),
            service('logger'),
            service('sylius.context.channel'),
        ])
        ->public();

    $services->set('bitbag_sylius_cms_plugin.resolver.page_resource', \BitBag\SyliusCmsPlugin\Resolver\PageResourceResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.page'),
            service('sylius.context.locale'),
            service('logger'),
        ])
        ->public();

    $services->set('bitbag_sylius_cms_plugin.resolver.media_resource', \BitBag\SyliusCmsPlugin\Resolver\MediaResourceResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.media'),
            service('sylius.context.locale'),
            service('sylius.context.channel'),
            service('logger'),
        ])
        ->public();

    $services->set('bitbag_sylius_cms_plugin.resolver.media_provider', \BitBag\SyliusCmsPlugin\Resolver\MediaProviderResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.registry.media_provider'),
        ])
        ->public();

    $services->set('bitbag_sylius_cms_plugin.resolver.importer_sections', \BitBag\SyliusCmsPlugin\Resolver\ImporterSectionsResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.assigner.sections'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.resolver.importer_channels', \BitBag\SyliusCmsPlugin\Resolver\ImporterChannelsResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.assigner.channels'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.resolver.importer_products', \BitBag\SyliusCmsPlugin\Resolver\ImporterProductsResolver::class)
        ->args([
            service('bitbag_sylius_cms_plugin.assigner.products'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.resolver.wysiwyg_strategy_resolver', \BitBag\SyliusCmsPlugin\Resolver\WysiwygStrategyResolver::class)
        ->args([
            [
                'ckeditor' => service('bitbag_sylius_cms_plugin.form.wysiwyg_strategy.ckeditor'),
                'trix' => service('bitbag_sylius_cms_plugin.form.wysiwyg_strategy.trix'),
            ],
            param('bitbag_sylius_cms_plugin.wysiwyg_editor'),
        ]);
};
