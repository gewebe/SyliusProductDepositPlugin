<?php

declare(strict_types=1);

namespace Gweb\SyliusDepositPlugin\OrderProcessor;

use Gweb\SyliusDepositPlugin\Entity\ProductVariant;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

/**
 * Recalculates the order item unit price inclusive deposit
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
class DepositOrderProcessor implements OrderProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        $channel = $order->getChannel();

        $items = $order->getItems();

        foreach ($items as $item) {
            /** @var ProductVariant $productVariant */
            $productVariant = $item->getVariant();

            if ($productVariant->hasChannelDepositForChannel($channel)) {
                $deposit = $productVariant->getChannelDepositForChannel($channel);

                $item->setUnitPrice($item->getUnitPrice() + $deposit->getPrice());
            }
        }

    }
}
