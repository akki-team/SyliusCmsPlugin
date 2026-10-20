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
use Doctrine\ORM\Tools\Pagination\Paginator;
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

        $page = max(1, (int) ($query['page'] ?? 1));
        $limit = max(1, (int) ($query['limit'] ?? 10));
        $queryBuilder = $this->mediaRepository
            ->createListQueryBuilder($this->localeContext->getLocaleCode())
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
        ;

        $phrase = trim((string) ($query['criteria']['search']['value'] ?? ''));
        if ('' !== $phrase) {
            $queryBuilder
                ->andWhere('o.code LIKE :phrase OR translation.name LIKE :phrase')
                ->setParameter('phrase', '%' . $phrase . '%')
            ;
        }

        $type = (string) ($query['criteria']['type'] ?? '');
        if ('' !== $type) {
            $queryBuilder
                ->andWhere('o.type = :type')
                ->setParameter('type', $type)
            ;
        }

        $paginator = new Paginator($queryBuilder);

        return new JsonResponse([
            'page' => $page,
            'limit' => $limit,
            'total' => count($paginator),
            '_embedded' => ['items' => $this->serialize(iterator_to_array($paginator))],
        ]);
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
