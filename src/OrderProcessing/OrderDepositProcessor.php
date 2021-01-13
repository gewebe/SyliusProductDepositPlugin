<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\OrderProcessing;

use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Sylius\Component\Addressing\Matcher\ZoneMatcherInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\Scope;
use Sylius\Component\Core\Provider\ZoneProviderInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Webmozart\Assert\Assert;

/**
 * Recalculates the order item unit price inclusive deposit
 */
final class OrderDepositProcessor implements OrderProcessorInterface
{
    /** @var ZoneProviderInterface */
    private $defaultTaxZoneProvider;

    /** @var ZoneMatcherInterface */
    private $zoneMatcher;

    /** @var OrderTaxesApplicatorInterface */
    private $orderDepositTaxesApplicator;

    /**
     * @param ZoneProviderInterface $defaultTaxZoneProvider
     * @param ZoneMatcherInterface $zoneMatcher
     * @param OrderTaxesApplicatorInterface $orderDepositTaxesApplicator
     */
    public function __construct(
        ZoneProviderInterface $defaultTaxZoneProvider,
        ZoneMatcherInterface $zoneMatcher,
        OrderTaxesApplicatorInterface $orderDepositTaxesApplicator
    ) {
        $this->defaultTaxZoneProvider = $defaultTaxZoneProvider;
        $this->zoneMatcher = $zoneMatcher;
        $this->orderDepositTaxesApplicator = $orderDepositTaxesApplicator;
    }

    public function process(BaseOrderInterface $order): void
    {
        Assert::isInstanceOf($order, OrderInterface::class);

        $channel = $order->getChannel();
        if (null === $channel) {
            return;
        }

        /** @var OrderItemInterface $item */
        foreach ($order->getItems() as $item) {

            /** @var ProductVariantInterface $variant */
            $variant = $item->getVariant();

            $channelDeposit = $variant->getChannelDepositForChannel($channel);
            if (null === $channelDeposit) {
                continue;
            }

            $depositPrice = $channelDeposit->getPrice();
            if (null === $depositPrice) {
                continue;
            }

            $item->setUnitPrice($item->getUnitPrice() + $depositPrice);
        }

        // apply deposit taxes
        $zone = $this->getTaxZone($order);
        if (null !== $zone) {
            $this->orderDepositTaxesApplicator->apply($order, $zone);
        }
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
