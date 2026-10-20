<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCmsPlugin\Controller\Action\Admin;

use BitBag\SyliusCmsPlugin\Entity\SectionInterface;
use BitBag\SyliusCmsPlugin\Repository\SectionRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SectionSearchAction
{
    public function __construct(private SectionRepositoryInterface $sectionRepository)
    {
    }

    public function __invoke(Request $request): Response
    {
        $codes = $request->query->all()['code'] ?? null;
        $sections = null !== $codes
            ? $this->sectionRepository->findBy(['code' => (array) $codes])
            : $this->sectionRepository->findByNamePart((string) $request->query->get('phrase', ''));

        return new JsonResponse(array_map(static fn (SectionInterface $section): array => [
            'id' => $section->getId(),
            'code' => $section->getCode(),
            'name' => $section->getName(),
        ], $sections));
    }
}
