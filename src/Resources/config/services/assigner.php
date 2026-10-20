<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.assigner.channels', \BitBag\SyliusCmsPlugin\Assigner\ChannelsAssigner::class)
        ->args([
            service('sylius.repository.channel'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.assigner.products', \BitBag\SyliusCmsPlugin\Assigner\ProductsAssigner::class)
        ->args([
            service('sylius.repository.product'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.assigner.taxons', \BitBag\SyliusCmsPlugin\Assigner\TaxonsAssigner::class)
        ->args([
            service('sylius.repository.taxon'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.assigner.sections', \BitBag\SyliusCmsPlugin\Assigner\SectionsAssigner::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.section'),
        ]);
};
