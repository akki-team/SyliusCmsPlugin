<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.controller.page_slug', \BitBag\SyliusCmsPlugin\Controller\PageSlugController::class)
        ->args([
            service('sylius.generator.slug'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.controller.action.admin.product_search', \BitBag\SyliusCmsPlugin\Controller\Action\Admin\ProductSearchAction::class)
        ->args([
            service('sylius.repository.product'),
            service('sylius.context.locale'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.controller.action.admin.taxon_search', \BitBag\SyliusCmsPlugin\Controller\Action\Admin\TaxonSearchAction::class)
        ->args([
            service('sylius.repository.taxon'),
            service('sylius.context.locale'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.controller.action.admin.import_data', \BitBag\SyliusCmsPlugin\Controller\Action\Admin\ImportDataAction::class)
        ->args([
            service('bitbag_sylius_cms_plugin.processor.import'),
            service('form.factory'),
            service('request_stack'),
            service('bitbag_sylius_cms_plugin.controller.helper.form_errors_flash'),
            service('translator'),
            service('twig'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.controller.action.admin.upload_editor_image', \BitBag\SyliusCmsPlugin\Controller\Action\Admin\UploadEditorImageAction::class)
        ->args([
            service('bitbag_sylius_cms_plugin.resolver.media_provider'),
            service('bitbag_sylius_cms_plugin.repository.media'),
            service('bitbag_sylius_cms_plugin.factory.media'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.controller.helper.form_errors_flash', \BitBag\SyliusCmsPlugin\Controller\Helper\FormErrorsFlashHelper::class)
        ->args([
            service('request_stack'),
            service('translator'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.controller.media.overriden', \BitBag\SyliusCmsPlugin\Controller\MediaController::class)
        ->parent('bitbag_sylius_cms_plugin.controller.media')
        ->call('setMediaProviderResolver', [
            service('bitbag_sylius_cms_plugin.resolver.media_provider'),
        ])
        ->call('setMediaResourceResolver', [
            service('bitbag_sylius_cms_plugin.resolver.media_resource'),
        ])
        ->call('setFormErrorsFlashHelper', [
            service('bitbag_sylius_cms_plugin.controller.helper.form_errors_flash'),
        ])
        ->call('setCacheManager', [
            service('liip_imagine.cache.manager'),
        ])
        ->call('setDataManager', [
            service('liip_imagine.data.manager'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.controller.page.overriden', \BitBag\SyliusCmsPlugin\Controller\PageController::class)
        ->parent('bitbag_sylius_cms_plugin.controller.page')
        ->call('setPageResourceResolver', [
            service('bitbag_sylius_cms_plugin.resolver.page_resource'),
        ])
        ->call('setFormErrorsFlashHelper', [
            service('bitbag_sylius_cms_plugin.controller.helper.form_errors_flash'),
        ])
        ->call('setCacheManager', [
            service('liip_imagine.cache.manager'),
        ])
        ->call('setDataManager', [
            service('liip_imagine.data.manager'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.controller.action.admin.retrieve_package_info', \BitBag\SyliusCmsPlugin\Controller\Action\Admin\RetrievePackageInfoAction::class);

    $services->set('bitbag_sylius_cms_plugin.controller.action.admin.section_search', \BitBag\SyliusCmsPlugin\Controller\Action\Admin\SectionSearchAction::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.section'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.controller.action.admin.media_search', \BitBag\SyliusCmsPlugin\Controller\Action\Admin\MediaSearchAction::class)
        ->args([
            service('bitbag_sylius_cms_plugin.repository.media'),
            service('sylius.context.locale'),
        ]);
};
