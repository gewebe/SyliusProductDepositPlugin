<?php

declare(strict_types=1);

namespace spec\Gewebe\SyliusProductDepositPlugin\OrderProcessing;

use Doctrine\Common\Collections\ArrayCollection;
use Gewebe\SyliusProductDepositPlugin\Entity\ChannelDepositInterface;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Gewebe\SyliusProductDepositPlugin\OrderProcessing\OrderDepositProcessor;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Addressing\Matcher\ZoneMatcherInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Provider\ZoneProviderInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class OrderDepositProcessorSpec extends ObjectBehavior
{
    function let(
        ZoneProviderInterface $defaultTaxZoneProvider,
        ZoneMatcherInterface $zoneMatcher,
        OrderTaxesApplicatorInterface $orderTaxesApplicator
    ): void {
        $this->beConstructedWith($defaultTaxZoneProvider, $zoneMatcher, $orderTaxesApplicator);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderDepositProcessor::class);
    }

    function it_implements_order_processor_interface(): void
    {
        $this->shouldImplement(OrderProcessorInterface::class);
    }

    function it_process_order_deposit(
        AddressInterface $address,
        ChannelInterface $channel,
        ChannelDepositInterface $channelDeposit,
        OrderInterface $order,
        OrderItemInterface $orderItem,
        OrderTaxesApplicatorInterface $orderTaxesApplicator,
        ProductVariantInterface $productVariant,
        ZoneProviderInterface $defaultTaxZoneProvider,
        ZoneMatcherInterface $zoneMatcher,
        ZoneInterface $zone
    ): void {
        $channelDeposit->getPrice()->willReturn(50);

        $productVariant->getChannelDepositForChannel($channel)->willReturn($channelDeposit);

        $orderItem->getVariant()->willReturn($productVariant);
        $orderItem->getUnitPrice()->willReturn(400);
        $orderItem->setUnitPrice(450)->shouldBeCalled();

        $order->getChannel()->willReturn($channel);
        $order->getItems()->willReturn(new ArrayCollection([$orderItem->getWrappedObject()]));
        $order->getShippingAddress()->willReturn($address);

        $defaultTaxZoneProvider->getZone($order)->willReturn($zone);
        $this->beConstructedWith($defaultTaxZoneProvider, $zoneMatcher, $orderTaxesApplicator);

        $this->process($order);
    }
}
