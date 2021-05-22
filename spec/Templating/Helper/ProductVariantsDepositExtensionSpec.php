<?php

declare(strict_types=1);

namespace spec\Gewebe\SyliusProductDepositPlugin\Templating\Helper;

use Gewebe\SyliusProductDepositPlugin\Templating\Helper\ProductVariantsDepositExtension;
use Gewebe\SyliusProductDepositPlugin\Templating\Helper\ProductVariantsDepositHelper;
use PhpSpec\ObjectBehavior;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ProductVariantsDepositExtensionSpec extends ObjectBehavior
{
    function let(
        ProductVariantsDepositHelper $productVariantsDepositHelper
    ): void {
        $this->beConstructedWith($productVariantsDepositHelper);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductVariantsDepositExtension::class);
    }

    function it_extends_twig_extension(): void
    {
        $this->shouldHaveType(AbstractExtension::class);
    }

    function it_returns_functions(): void
    {
        $functions = $this->getFunctions();
        $functions->shouldHaveCount(3);
        foreach ($functions as $function) {
            $function->shouldHaveType(TwigFunction::class);
        }
    }
}
