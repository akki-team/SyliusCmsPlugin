<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusCmsPlugin\Controller\Action\Admin;

use BitBag\SyliusCmsPlugin\Controller\Action\Admin\SectionSearchAction;
use BitBag\SyliusCmsPlugin\Entity\SectionInterface;
use BitBag\SyliusCmsPlugin\Repository\SectionRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;

final class SectionSearchActionSpec extends ObjectBehavior
{
    public function let(SectionRepositoryInterface $sectionRepository): void
    {
        $this->beConstructedWith($sectionRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(SectionSearchAction::class);
    }

    public function it_searches_sections_by_name_phrase(
        SectionRepositoryInterface $sectionRepository,
        SectionInterface $section,
    ): void {
        $section->getId()->willReturn(3);
        $section->getCode()->willReturn('blog');
        $section->getName()->willReturn('Blog');
        $sectionRepository->findByNamePart('bl')->willReturn([$section]);

        $response = $this->__invoke(new Request(['phrase' => 'bl']));

        $response->getContent()->shouldReturn('[{"id":3,"code":"blog","name":"Blog"}]');
    }

    public function it_finds_sections_by_codes(
        SectionRepositoryInterface $sectionRepository,
        SectionInterface $section,
    ): void {
        $section->getId()->willReturn(4);
        $section->getCode()->willReturn('faq');
        $section->getName()->willReturn('FAQ');
        $sectionRepository->findBy(['code' => ['faq']])->willReturn([$section]);
        $sectionRepository->findByNamePart()->shouldNotBeCalled();

        $response = $this->__invoke(new Request(['code' => ['faq']]));

        $response->getContent()->shouldReturn('[{"id":4,"code":"faq","name":"FAQ"}]');
    }
}
