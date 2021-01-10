<?php

declare(strict_types=1);

namespace spec\Gewebe\SyliusProductDepositPlugin\Entity;

use Gewebe\SyliusProductDepositPlugin\Entity\ChannelDeposit;
use Gewebe\SyliusProductDepositPlugin\Entity\ChannelDepositInterface;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use PhpSpec\ObjectBehavior;

final class ChannelDepositSpec extends ObjectBehavior
{
    function it_is_channel_deposit(): void
    {
        $this->shouldHaveType(ChannelDeposit::class);
    }

    function it_implements_channel_deposit_interface(): void
    {
        $this->shouldImplement(ChannelDepositInterface::class);
    }

    function it_has_price(): void
    {
        $this->getPrice()->shouldReturn(null);
        $this->setPrice(25);
        $this->getPrice()->shouldReturn(25);
    }

    function it_has_product_variant(ProductVariantInterface $productVariant): void
    {
        $this->getProductVariant()->shouldReturn(null);
        $this->setProductVariant($productVariant);
        $this->getProductVariant()->shouldReturn($productVariant);
    }

    function is_has_channel_code(): void
    {
        $this->getChannelCode()->shouldReturn(null);
        $this->setChannelCode(null);
        $this->setChannelCode('de');
        $this->getChannelCode()->shouldReturn('de');
    }
}
