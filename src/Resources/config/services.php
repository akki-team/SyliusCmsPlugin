<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->import('services/*.php');

    $parameters = $container->parameters();
    $parameters->set('bitbag_sylius_cms_plugin.media_provider.interface', 'BitBag\\SyliusCmsPlugin\\MediaProvider\\ProviderInterface');

    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.reader.csv', \BitBag\SyliusCmsPlugin\Reader\CsvReader::class);

    $services->set('bitbag_sylius_cms_plugin.processor.import', \BitBag\SyliusCmsPlugin\Processor\ImportProcessor::class)
        ->args([
            service('bitbag_sylius_cms_plugin.importer.chain'),
            service('bitbag_sylius_cms_plugin.reader.csv'),
            service('doctrine.orm.entity_manager'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.command.import_from_csv', \BitBag\SyliusCmsPlugin\Command\ImportFromCsvCommand::class)
        ->args([
            service('bitbag_sylius_cms_plugin.processor.import'),
        ])
        ->tag('console.command');

    $services->set('bitbag_sylius_cms_plugin.downloader.image', \BitBag\SyliusCmsPlugin\Downloader\ImageDownloader::class)
        ->args([
            service('filesystem'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.registry.media_provider', \Sylius\Component\Registry\ServiceRegistry::class)
        ->args([
            param('bitbag_sylius_cms_plugin.media_provider.interface'),
            'Media provider',
        ]);

    $services->set('bitbag_sylius_cms_plugin.media_uploader', \BitBag\SyliusCmsPlugin\Uploader\MediaUploader::class)
        ->args([
            service('bitbag_sylius_cms_plugin.media.filesystem'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.media.filesystem', \Sylius\Component\Core\Filesystem\Adapter\FlysystemFilesystemAdapter::class)
        ->args([
            service('bitbag_sylius_cms_plugin.storage'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.sorter.sections', \BitBag\SyliusCmsPlugin\Sorter\SectionsSorter::class);
};
