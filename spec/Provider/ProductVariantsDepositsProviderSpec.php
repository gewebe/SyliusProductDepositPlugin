<?php

declare(strict_types=1);

namespace spec\Gewebe\SyliusProductDepositPlugin\Provider;

use Doctrine\Common\Collections\ArrayCollection;
use Gewebe\SyliusProductDepositPlugin\Entity\ChannelDepositInterface;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Gewebe\SyliusProductDepositPlugin\Provider\ProductVariantsDepositsProvider;
use Gewebe\SyliusProductDepositPlugin\Provider\ProductVariantsDepositsProviderInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

final class ProductVariantsDepositsProviderSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductVariantsDepositsProvider::class);
    }

    function it_implements_product_variants_deposits_provider(): void
    {
        $this->shouldImplement(ProductVariantsDepositsProviderInterface::class);
    }

    function it_provide_product_variants_deposit(
        ProductInterface $product,
        ProductVariantInterface $productVariant,
        ProductOptionValueInterface $productOptionValue,
        ChannelInterface $channel,
        ChannelDepositInterface $channelDeposit
    ): void {
        $channelDeposit->getPrice()->willReturn(50);
        $productVariant->getChannelDepositForChannel($channel)->willReturn($channelDeposit);

        $productOptionValue->getCode()->willReturn('1 liter');
        $productOptionValue->getOptionCode()->willReturn('1_liter');
        $productOptionValues = new ArrayCollection([$productOptionValue->getWrappedObject()]);
        $productVariant->getOptionValues()->willReturn($productOptionValues);

        $variants = new ArrayCollection([$productVariant->getWrappedObject()]);
        $product->getVariants()->willReturn($variants);

        $variantsDeposit = $this->provideVariantsDeposits($product, $channel);
        $variantsDeposit->shouldHaveCount(1);
        $variantsDeposit->shouldReturn([
            0 => [
                '1_liter' => '1 liter',
                'value' => 50
            ]
        ]);
    }
}
