<?php

declare(strict_types=1);

namespace spec\Gewebe\SyliusProductDepositPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Gewebe\SyliusProductDepositPlugin\Entity\ChannelDepositInterface;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariant;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

final class ProductVariantSpec extends ObjectBehavior
{
    function it_is_sylius_product_variant(): void
    {
        $this->shouldHaveType(ProductVariant::class);
    }

    function it_implements_product_variant_interface(): void
    {
        $this->shouldImplement(ProductVariantInterface::class);
    }

    function it_has_no_default_channel_deposits(): void
    {
        $this->getChannelDeposits()->shouldReturnAnInstanceOf(Collection::class);
        $this->getChannelDeposits()->shouldHaveCount(0);
    }

    function it_has_channel_deposit_for_channel(ChannelInterface $channel, ChannelDepositInterface $channelDeposit): void
    {
        $channel->getCode()->willReturn('de');

        $channelDeposit->getPrice()->willReturn(100);
        $channelDeposit->getChannelCode()->willReturn('de');
        $channelDeposit->setProductVariant($this)->shouldBeCalled();
        $channelDeposit->setProductVariant(null)->shouldBeCalled();

        $this->hasChannelDeposit($channelDeposit)->shouldReturn(false);
        $this->getDepositPriceByChannel($channel)->shouldReturn(null);

        $this->addChannelDeposit($channelDeposit);

        $this->hasChannelDeposit($channelDeposit)->shouldReturn(true);
        $this->getDepositPriceByChannel($channel)->shouldReturn(100);

        $this->removeChannelDeposit($channelDeposit);

        $this->hasChannelDeposit($channelDeposit)->shouldReturn(false);
        $this->getDepositPriceByChannel($channel)->shouldReturn(null);
    }

    function it_has_tax_category(TaxCategoryInterface $taxCategory): void
    {
        $this->getDepositTaxCategory()->shouldReturn(null);
        $this->setDepositTaxCategory($taxCategory);
        $this->getDepositTaxCategory()->shouldReturn($taxCategory);
    }
}
