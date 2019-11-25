<?php

declare(strict_types=1);

namespace spec\Gweb\SyliusProductDepositPlugin\Templating\Helper;

use Gweb\SyliusProductDepositPlugin\Entity\ChannelDepositInterface;
use Gweb\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Gweb\SyliusProductDepositPlugin\Provider\ProductVariantsDepositsProviderInterface;
use Gweb\SyliusProductDepositPlugin\Templating\Helper\ProductVariantsDepositHelper;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Templating\Helper\Helper;

class ProductVariantsDepositHelperSpec extends ObjectBehavior
{
    function let(
        ProductVariantsDepositsProviderInterface $productVariantsDepositsProvider
    ): void {
        $this->beConstructedWith($productVariantsDepositsProvider);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductVariantsDepositHelper::class);
    }

    function it_extends_templating_helper(): void
    {
        $this->shouldHaveType(Helper::class);
    }

    function it_returns_single_product_variant_deposit_price(
        ProductVariantInterface $productVariant,
        ChannelInterface $channel,
        ChannelDepositInterface $channelDeposit
    ): void {
        $channelDeposit->getPrice()->willReturn(50);

        $productVariant->getChannelDepositForChannel($channel)->willReturn($channelDeposit);

        $this->getDeposit($productVariant, $channel)->shouldReturn(50);
    }

    function it_returns_all_product_variants_deposit_prices(
        ProductVariantsDepositsProviderInterface $productVariantsDepositsProvider,
        ProductInterface $product,
        ChannelInterface $channel
    ): void {
        $productVariantsDepositsProvider->provideVariantsDeposits($product, $channel)->willReturn([1 => 50, 2 => 100]);
        $this->beConstructedWith($productVariantsDepositsProvider);

        $this->getDepositsByProduct($product, $channel)->shouldReturn([1 => 50, 2 => 100]);
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('gweb_product_variants_deposit');
    }
}
