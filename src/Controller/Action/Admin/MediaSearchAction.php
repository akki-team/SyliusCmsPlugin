<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCmsPlugin\Controller\Action\Admin;

use BitBag\SyliusCmsPlugin\Entity\MediaInterface;
use BitBag\SyliusCmsPlugin\Repository\MediaRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MediaSearchAction
{
    public function __construct(
        private MediaRepositoryInterface $mediaRepository,
        private LocaleContextInterface $localeContext,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $query = $request->query->all();
        if (isset($query['code'])) {
            return new JsonResponse($this->serialize($this->mediaRepository->findBy(['code' => (array) $query['code']])));
        }

        $queryBuilder = $this->mediaRepository
            ->createListQueryBuilder($this->localeContext->getLocaleCode())
            ->setMaxResults(max(1, (int) ($query['limit'] ?? 10)))
        ;

        $phrase = trim((string) ($query['criteria']['search']['value'] ?? ''));
        if ('' !== $phrase) {
            $queryBuilder
                ->andWhere('o.code LIKE :phrase OR translation.name LIKE :phrase')
                ->setParameter('phrase', '%' . $phrase . '%')
            ;
        }

        if (isset($query['mediaType']) && '' !== $query['mediaType']) {
            $queryBuilder
                ->andWhere('o.type = :mediaType')
                ->setParameter('mediaType', $query['mediaType'])
            ;
        }

        return new JsonResponse(['_embedded' => ['items' => $this->serialize($queryBuilder->getQuery()->getResult())]]);
    }

    /** @param array<array-key, mixed> $media */
    private function serialize(array $media): array
    {
        return array_map(static fn (MediaInterface $item): array => [
            'id' => $item->getId(),
            'code' => $item->getCode(),
            'path' => $item->getPath(),
            'name' => $item->getName(),
        ], $media);
    }
}
