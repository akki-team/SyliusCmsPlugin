<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCmsPlugin\Controller;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use BitBag\SyliusCmsPlugin\Entity\PageTranslationInterface;
use BitBag\SyliusCmsPlugin\Resolver\PageResourceResolverInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PageController extends ResourceController
{
    use ResourceDataProcessingTrait;
    use MediaPageControllersCommonDependencyInjectionsTrait;

    /** @var PageResourceResolverInterface */
    private $pageResourceResolver;

    public const FILTER = 'sylius_admin_product_original';

    public function renderLinkAction(Request $request): Response
    {
        $configuration = $this->getRequestConfiguration($request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);

        $code = $request->attributes->get('code', $request->query->get('code'));

        $page = $this->pageResourceResolver->findOrLog($code);

        if (null === $page) {
            return new Response();
        }

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $page);

        if ($configuration->isHtmlRequest()) {
            return $this->render($configuration->getTemplate(ResourceActions::SHOW . '.html'), [
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $page,
                $this->metadata->getName() => $page,
            ]);
        }

        return $this->createRestView($configuration, $page);
    }

    public function previewAction(Request $request): Response
    {
        $configuration = $this->getRequestConfiguration($request);

        $this->isGrantedOr403($configuration, ResourceActions::CREATE);

        /** @var PageInterface $page */
        $page = $this->getResourceInterface($request);
        $form = $this->getFormForResource($configuration, $page);
        $defaultLocale = (string) $this->getParameter('locale');

        $form->handleRequest($request);

        $page->setFallbackLocale($request->attributes->get('_locale', $request->query->get('_locale', $defaultLocale)));
        $page->setCurrentLocale($request->attributes->get('_locale', $request->query->get('_locale', $defaultLocale)));

        $this->resolveImage($page);

        $this->formErrorsFlashHelper->addFlashErrors($form);

        if (!$configuration->isHtmlRequest()) {
            return $this->createRestView($configuration, $page);
        }

        return $this->render($configuration->getTemplate(ResourceActions::CREATE . '.html'), [
            'resource' => $page,
            'preview' => true,
            $this->metadata->getName() => $page,
        ]);
    }

    private function resolveImage(PageInterface $page): void
    {
        /** @var PageTranslationInterface $translation */
        $translation = $page->getTranslation();

        $image = $translation->getImage();

        if (null === $image || null === $image->getPath()) {
            return;
        }
        $this->setResourceMediaPath($image);
        /** @var PageTranslationInterface $pageTranslationInterface */
        $pageTranslationInterface = $page->getTranslation();
        $pageTranslationInterface->setImage($image);
    }

    public function setPageResourceResolver(PageResourceResolverInterface $pageResourceResolver): void
    {
        $this->pageResourceResolver = $pageResourceResolver;
    }
}
