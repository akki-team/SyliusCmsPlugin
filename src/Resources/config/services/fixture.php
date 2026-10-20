<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->public();

    $services->set('bitbag_sylius_cms_plugin.fixture.block', \BitBag\SyliusCmsPlugin\Fixture\BlockFixture::class)
        ->args([
            service('bitbag_sylius_cms_plugin.fixture.factory.block'),
        ])
        ->tag('sylius_fixtures.fixture');

    $services->set('bitbag_sylius_cms_plugin.fixture.page', \BitBag\SyliusCmsPlugin\Fixture\PageFixture::class)
        ->args([
            service('bitbag_sylius_cms_plugin.fixture.factory.page'),
        ])
        ->tag('sylius_fixtures.fixture');

    $services->set('bitbag_sylius_cms_plugin.fixture.frequently_asked_question', \BitBag\SyliusCmsPlugin\Fixture\FrequentlyAskedQuestionFixture::class)
        ->args([
            service('bitbag_sylius_cms_plugin.fixture.factory.frequently_asked_question'),
        ])
        ->tag('sylius_fixtures.fixture');

    $services->set('bitbag_sylius_cms_plugin.fixture.section', \BitBag\SyliusCmsPlugin\Fixture\SectionFixture::class)
        ->args([
            service('bitbag_sylius_cms_plugin.fixture.factory.section'),
        ])
        ->tag('sylius_fixtures.fixture');

    $services->set('bitbag_sylius_cms_plugin.fixture.media', \BitBag\SyliusCmsPlugin\Fixture\MediaFixture::class)
        ->args([
            service('bitbag_sylius_cms_plugin.fixture.factory.media'),
        ])
        ->tag('sylius_fixtures.fixture');

    $services->set('bitbag_sylius_cms_plugin.fixture.factory.block', \BitBag\SyliusCmsPlugin\Fixture\Factory\BlockFixtureFactory::class)
        ->args([
            service('bitbag_sylius_cms_plugin.factory.block'),
            service('bitbag_sylius_cms_plugin.factory.block_translation'),
            service('bitbag_sylius_cms_plugin.repository.block'),
            service('sylius.repository.product'),
            service('sylius.context.channel'),
            service('sylius.context.locale'),
            service('bitbag_sylius_cms_plugin.assigner.products'),
            service('bitbag_sylius_cms_plugin.assigner.taxons'),
            service('bitbag_sylius_cms_plugin.assigner.sections'),
            service('bitbag_sylius_cms_plugin.assigner.channels'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.fixture.factory.page', \BitBag\SyliusCmsPlugin\Fixture\Factory\PageFixtureFactory::class)
        ->args([
            service('bitbag_sylius_cms_plugin.factory.page'),
            service('bitbag_sylius_cms_plugin.factory.page_translation'),
            service('bitbag_sylius_cms_plugin.repository.page'),
            service('bitbag_sylius_cms_plugin.resolver.media_provider'),
            service('bitbag_sylius_cms_plugin.assigner.products'),
            service('bitbag_sylius_cms_plugin.assigner.sections'),
            service('bitbag_sylius_cms_plugin.assigner.channels'),
            service('sylius.repository.product'),
            service('sylius.context.locale'),
            service('sylius.repository.channel'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.fixture.factory.frequently_asked_question', \BitBag\SyliusCmsPlugin\Fixture\Factory\FrequentlyAskedQuestionFixtureFactory::class)
        ->args([
            service('bitbag_sylius_cms_plugin.factory.frequently_asked_question'),
            service('bitbag_sylius_cms_plugin.factory.frequently_asked_question_translation'),
            service('bitbag_sylius_cms_plugin.repository.frequently_asked_question'),
            service('bitbag_sylius_cms_plugin.assigner.channels'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.fixture.factory.section', \BitBag\SyliusCmsPlugin\Fixture\Factory\SectionFixtureFactory::class)
        ->args([
            service('bitbag_sylius_cms_plugin.factory.section'),
            service('bitbag_sylius_cms_plugin.factory.section_translation'),
            service('bitbag_sylius_cms_plugin.repository.section'),
        ]);

    $services->set('bitbag_sylius_cms_plugin.fixture.factory.media', \BitBag\SyliusCmsPlugin\Fixture\Factory\MediaFixtureFactory::class)
        ->args([
            service('bitbag_sylius_cms_plugin.factory.media'),
            service('bitbag_sylius_cms_plugin.factory.media_translation'),
            service('bitbag_sylius_cms_plugin.resolver.media_provider'),
            service('bitbag_sylius_cms_plugin.repository.media'),
            service('bitbag_sylius_cms_plugin.assigner.products'),
            service('bitbag_sylius_cms_plugin.assigner.sections'),
            service('bitbag_sylius_cms_plugin.assigner.channels'),
        ]);
};
