<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();
    $parameters->set('bitbag_cms.twig.admin_functions', [
        'bitbag_cms_render_block',
        'bitbag_cms_render_media',
    ]);
    $parameters->set('bitbag_cms.twig.link_template', '@BitBagSyliusCmsPlugin/Page/link.html.twig');

    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.twig.extension.block', \BitBag\SyliusCmsPlugin\Twig\Extension\RenderBlockExtension::class)
        ->args([
            service('bitbag_sylius_cms_plugin.twig.runtime.block'),
        ])
        ->tag('twig.extension');

    $services->set('bitbag_sylius_cms_plugin.twig.runtime.block', \BitBag\SyliusCmsPlugin\Twig\Runtime\RenderBlockRuntime::class)
        ->args([
            service('bitbag_sylius_cms_plugin.resolver.block_resource'),
            service('twig'),
        ])
        ->tag('twig.runtime');

    $services->set('bitbag_sylius_cms_plugin.twig.extension.media', \BitBag\SyliusCmsPlugin\Twig\Extension\RenderMediaExtension::class)
        ->args([
            service('bitbag_sylius_cms_plugin.twig.runtime.media'),
        ])
        ->tag('twig.extension');

    $services->set('bitbag_sylius_cms_plugin.twig.runtime.media', \BitBag\SyliusCmsPlugin\Twig\Runtime\RenderMediaRuntime::class)
        ->args([
            service('bitbag_sylius_cms_plugin.resolver.media_provider'),
            service('bitbag_sylius_cms_plugin.resolver.media_resource'),
        ])
        ->tag('twig.runtime');

    $services->set('bitbag_sylius_cms_plugin.twig.extension.product_pages', \BitBag\SyliusCmsPlugin\Twig\Extension\RenderProductPagesExtension::class)
        ->args([
            service('bitbag_sylius_cms_plugin.twig.runtime.product_pages'),
        ])
        ->tag('twig.extension');

    $services->set('bitbag_sylius_cms_plugin.twig.runtime.product_pages', \BitBag\SyliusCmsPlugin\Twig\Runtime\RenderProductPagesRuntime::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.page'),
            service('sylius.context.channel'),
            service('twig'),
            service('bitbag_sylius_cms_plugin.sorter.sections'),
        ])
        ->tag('twig.runtime');

    $services->set('bitbag_sylius_cms_plugin.twig.extension.render_content', \BitBag\SyliusCmsPlugin\Twig\Extension\RenderContentExtension::class)
        ->tag('twig.extension');

    $services->set('bitbag_sylius_cms_plugin.twig.runtime.render_content', \BitBag\SyliusCmsPlugin\Twig\Runtime\RenderContentRuntime::class)
        ->args([
            service('bitbag_sylius_cms_plugin.twig.parser.content'),
        ])
        ->tag('twig.runtime');

    $services->set('bitbag_sylius_cms_plugin.twig.parser.content', \BitBag\SyliusCmsPlugin\Twig\Parser\ContentParser::class)
        ->args([
            service('twig'),
            param('bitbag_cms.twig.admin_functions'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.twig.extension.render_link', \BitBag\SyliusCmsPlugin\Twig\Extension\RenderLinkExtension::class)
        ->tag('twig.extension');

    $services->set('bitbag_sylius_cms_plugin.twig.runtime.render_link', \BitBag\SyliusCmsPlugin\Twig\Runtime\RenderLinkRuntime::class)
        ->args([
            service('sylius.context.locale'),
            service('bitbag_sylius_cms_plugin.repository.page'),
            service('router.default'),
            param('bitbag_cms.twig.link_template'),
        ])
        ->tag('twig.runtime');
};
