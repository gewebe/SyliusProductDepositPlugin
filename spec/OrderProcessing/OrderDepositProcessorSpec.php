<?php

declare(strict_types=1);

namespace spec\Gewebe\SyliusProductDepositPlugin\OrderProcessing;

use Doctrine\Common\Collections\ArrayCollection;
use Gewebe\SyliusProductDepositPlugin\Entity\AdjustmentInterface;
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
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Component\Core\Provider\ZoneProviderInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class OrderDepositProcessorSpec extends ObjectBehavior
{
    function let(
        AdjustmentFactoryInterface $adjustmentFactory,
        OrderTaxesApplicatorInterface $orderTaxesApplicator,
        ZoneMatcherInterface $zoneMatcher,
        ZoneProviderInterface $defaultTaxZoneProvider
    ): void {
        $this->beConstructedWith(
            $adjustmentFactory,
            $orderTaxesApplicator,
            $zoneMatcher,
            $defaultTaxZoneProvider
        );
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
        AdjustmentInterface $adjustment,
        AdjustmentFactoryInterface $adjustmentFactory,
        ChannelInterface $channel,
        OrderInterface $order,
        OrderItemInterface $orderItem,
        OrderItemUnitInterface $orderItemUnit,
        OrderTaxesApplicatorInterface $orderTaxesApplicator,
        ProductVariantInterface $productVariant,
        ZoneProviderInterface $defaultTaxZoneProvider,
        ZoneMatcherInterface $zoneMatcher,
        ZoneInterface $zone
    ): void {
        $order->getChannel()->willReturn($channel);
        $order->getItems()->willReturn(new ArrayCollection([$orderItem->getWrappedObject()]));
        $order->getShippingAddress()->willReturn($address);

        $orderItem->getVariant()->willReturn($productVariant);
        $orderItem->getUnits()->willReturn(new ArrayCollection([$orderItemUnit->getWrappedObject()]));

        $productVariant->getDepositPriceByChannel($channel)->willReturn(50);

        $orderItemUnit->addAdjustment($adjustment)->shouldBeCalled();

        $adjustmentFactory->createWithData(
            AdjustmentInterface::DEPOSIT_ADJUSTMENT,
            'Deposit',
            50
        )->willReturn($adjustment);

        $defaultTaxZoneProvider->getZone($order)->willReturn($zone);
        $this->beConstructedWith($adjustmentFactory, $orderTaxesApplicator, $zoneMatcher, $defaultTaxZoneProvider);

        $this->process($order);
    }
}
