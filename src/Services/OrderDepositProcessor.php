<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Services;

use Sylius\Component\Addressing\Matcher\ZoneMatcherInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\Scope;
use Sylius\Component\Core\Provider\ZoneProviderInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

/**
 * Recalculates the order item unit price inclusive deposit
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
final class OrderDepositProcessor implements OrderProcessorInterface
{
    /**
     * @var ZoneProviderInterface
     */
    private $defaultTaxZoneProvider;

    /**
     * @var ZoneMatcherInterface
     */
    private $zoneMatcher;
    /**
     * @var OrderDepositTaxesApplicator
     */
    private $orderDepositTaxesApplicator;

    /**
     * @param ZoneProviderInterface $defaultTaxZoneProvider
     * @param ZoneMatcherInterface $zoneMatcher
     * @param OrderDepositTaxesApplicator $orderDepositTaxesApplicator
     */
    public function __construct(
        ZoneProviderInterface $defaultTaxZoneProvider,
        ZoneMatcherInterface $zoneMatcher,
        OrderDepositTaxesApplicator $orderDepositTaxesApplicator
    ) {
        $this->defaultTaxZoneProvider = $defaultTaxZoneProvider;
        $this->zoneMatcher = $zoneMatcher;
        $this->orderDepositTaxesApplicator = $orderDepositTaxesApplicator;
    }

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        $channel = $order->getChannel();

        foreach ($order->getItems() as $item) {

            if (!$item->getVariant()->hasChannelDepositForChannel($channel)) {
                continue;
            }

            $depositPrice = $item->getVariant()->getChannelDepositForChannel($channel)->getPrice();

            $item->setUnitPrice($item->getUnitPrice() + $depositPrice);
        }

        // apply deposit taxes
        $this->orderDepositTaxesApplicator->apply($order, $this->getTaxZone($order));
    }

    /**
     * @param OrderInterface $order
     *
     * @return ZoneInterface|null
     */
    private function getTaxZone(OrderInterface $order): ?ZoneInterface
    {
        $shippingAddress = $order->getShippingAddress();
        $zone = null;

        if (null !== $shippingAddress) {
            $zone = $this->zoneMatcher->match($shippingAddress, Scope::TAX);
        }

        return $zone ?: $this->defaultTaxZoneProvider->getZone($order);
    }

}
