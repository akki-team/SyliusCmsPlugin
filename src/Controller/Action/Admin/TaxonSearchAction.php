<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCmsPlugin\Controller\Action\Admin;

use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TaxonSearchAction
{
    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
        private LocaleContextInterface $localeContext,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $items = $this->taxonRepository->findByNamePart((string) $request->query->get('phrase', ''), $this->localeContext->getLocaleCode());

        return new JsonResponse(array_map(static fn ($item): array => [
            'id' => $item->getId(),
            'code' => $item->getCode(),
            'name' => $item->getName(),
        ], $items));
    }
}
