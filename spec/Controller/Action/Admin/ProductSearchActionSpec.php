<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusCmsPlugin\Controller\Action\Admin;

use BitBag\SyliusCmsPlugin\Controller\Action\Admin\ProductSearchAction;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\Request;

final class ProductSearchActionSpec extends ObjectBehavior
{
    public function let(ProductRepositoryInterface $productRepository, LocaleContextInterface $localeContext): void
    {
        $localeContext->getLocaleCode()->willReturn('en_US');
        $this->beConstructedWith($productRepository, $localeContext);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductSearchAction::class);
    }

    public function it_returns_products_matching_the_phrase_as_json(
        ProductRepositoryInterface $productRepository,
        ProductInterface $product,
    ): void {
        $product->getId()->willReturn(7);
        $product->getCode()->willReturn('T_SHIRT');
        $product->getName()->willReturn('T-shirt');
        $productRepository->findByNamePart('shirt', 'en_US')->willReturn([$product]);

        $response = $this->__invoke(new Request(['phrase' => 'shirt']));

        $response->getContent()->shouldReturn('[{"id":7,"code":"T_SHIRT","name":"T-shirt"}]');
    }
}
