<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.form.type.block', \BitBag\SyliusCmsPlugin\Form\Type\BlockType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.block.class'),
            param('bitbag_sylius_cms_plugin.form.type.block.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.translation.block', \BitBag\SyliusCmsPlugin\Form\Type\Translation\BlockTranslationType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.block_translation.class'),
            param('bitbag_sylius_cms_plugin.form.type.translation.block.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.block_image', \BitBag\SyliusCmsPlugin\Form\Type\BlockImageType::class)
        ->args([
            'BitBag\\SyliusCmsPlugin\\Entity\\BlockImage',
            param('bitbag_sylius_cms_plugin.form.type.block_image.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.page', \BitBag\SyliusCmsPlugin\Form\Type\PageType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.page.class'),
            param('bitbag_sylius_cms_plugin.form.type.page.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.translation.page', \BitBag\SyliusCmsPlugin\Form\Type\Translation\PageTranslationType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.page_translation.class'),
            param('bitbag_sylius_cms_plugin.form.type.translation.page.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.frequently_asked_question', \BitBag\SyliusCmsPlugin\Form\Type\FrequentlyAskedQuestionType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.frequently_asked_question.class'),
            param('bitbag_sylius_cms_plugin.form.type.frequently_asked_question.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.translation.frequently_asked_question', \BitBag\SyliusCmsPlugin\Form\Type\Translation\FrequentlyAskedQuestionTranslationType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.frequently_asked_question_translation.class'),
            param('bitbag_sylius_cms_plugin.form.type.translation.frequently_asked_question.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.section', \BitBag\SyliusCmsPlugin\Form\Type\SectionType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.section.class'),
            param('bitbag_sylius_cms_plugin.form.type.section.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.translation.section', \BitBag\SyliusCmsPlugin\Form\Type\Translation\SectionTranslationType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.section_translation.class'),
            param('bitbag_sylius_cms_plugin.form.type.translation.section.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.import', \BitBag\SyliusCmsPlugin\Form\Type\ImportType::class)
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.translation.media', \BitBag\SyliusCmsPlugin\Form\Type\Translation\MediaTranslationType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.media_translation.class'),
            param('bitbag_sylius_cms_plugin.form.type.translation.media.validation_groups'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.media', \BitBag\SyliusCmsPlugin\Form\Type\MediaType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.media.class'),
            param('bitbag_sylius_cms_plugin.form.type.media.validation_groups'),
            param('bitbag_sylius_cms_plugin.media_providers'),
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.type.wysiwyg', \BitBag\SyliusCmsPlugin\Form\Type\WysiwygType::class)
        ->args([
            service('bitbag_sylius_cms_plugin.resolver.wysiwyg_strategy_resolver'),
        ])
        ->call('setStrategy', [
            '%bitbag_sylius_cms_plugin.wysiwyg_editor%',
        ])
        ->tag('form.type');

    $services->set('bitbag_sylius_cms_plugin.form.wysiwyg_strategy.ckeditor', \BitBag\SyliusCmsPlugin\Form\Strategy\Wysiwyg\CkeditorStrategy::class)
        ->args([
            service('router.default'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.form.wysiwyg_strategy.trix', \BitBag\SyliusCmsPlugin\Form\Strategy\Wysiwyg\TrixStrategy::class);

    $services->set('bitbag_sylius_cms_plugin.form.type.section_autocomplete_choice', \BitBag\SyliusCmsPlugin\Form\Type\SectionAutocompleteChoiceType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.section.class'),
        ])
        ->tag('form.type')
        ->tag('ux.entity_autocomplete_field');

    $services->set('bitbag_sylius_cms_plugin.form.type.media_autocomplete_choice', \BitBag\SyliusCmsPlugin\Form\Type\MediaAutocompleteChoiceType::class)
        ->args([
            param('bitbag_sylius_cms_plugin.model.media.class'),
        ])
        ->tag('form.type')
        ->tag('ux.entity_autocomplete_field');
};
