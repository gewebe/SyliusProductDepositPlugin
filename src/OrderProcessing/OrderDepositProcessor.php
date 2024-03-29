<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\OrderProcessing;

use Gewebe\SyliusProductDepositPlugin\Entity\AdjustmentInterface;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Sylius\Component\Addressing\Matcher\ZoneMatcherInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\Scope;
use Sylius\Component\Core\Provider\ZoneProviderInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Model\OrderItemUnitInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Webmozart\Assert\Assert;

/**
 * Apply deposit adjustment to order item units
 */
final class OrderDepositProcessor implements OrderProcessorInterface
{
    public function __construct(
        private AdjustmentFactoryInterface $adjustmentFactory,
        private OrderTaxesApplicatorInterface $orderDepositTaxesApplicator,
        private ZoneMatcherInterface $zoneMatcher,
        private ZoneProviderInterface $defaultTaxZoneProvider
    ) {
    }

    public function process(BaseOrderInterface $order): void
    {
        Assert::isInstanceOf($order, OrderInterface::class);

        $channel = $order->getChannel();
        if (null === $channel) {
            return;
        }

        foreach ($order->getItems() as $item) {
            /** @var ProductVariantInterface $variant */
            $variant = $item->getVariant();

            $depositPrice = $variant->getDepositPriceByChannel($channel);
            if (null === $depositPrice) {
                continue;
            }

            foreach ($item->getUnits() as $unit) {
                $this->addAdjustment($unit, $depositPrice);
            }
        }

        // apply deposit taxes
        $zone = $this->getTaxZone($order);
        if (null !== $zone) {
            $this->orderDepositTaxesApplicator->apply($order, $zone);
        }
    }


    private function addAdjustment(OrderItemUnitInterface $unit, int $amount): void
    {
        $adjustment = $this->adjustmentFactory->createWithData(
            AdjustmentInterface::DEPOSIT_ADJUSTMENT,
            'Deposit',
            $amount
        );

        $unit->addAdjustment($adjustment);
    }

    private function getTaxZone(OrderInterface $order): ?ZoneInterface
    {
        $shippingAddress = $order->getShippingAddress();
        $zone = null;

        if (null !== $shippingAddress) {
            $zone = $this->zoneMatcher->match($shippingAddress, Scope::TAX);
        }

        return $zone ?? $this->defaultTaxZoneProvider->getZone($order);
    }
}
