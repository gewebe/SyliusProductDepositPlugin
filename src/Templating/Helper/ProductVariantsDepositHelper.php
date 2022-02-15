<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Templating\Helper;

use Gewebe\SyliusProductDepositPlugin\Entity\AdjustmentInterface;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Gewebe\SyliusProductDepositPlugin\Provider\ProductVariantsDepositsProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\OrderItemUnit;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * Template helper to get product variant deposit prices
 */
class ProductVariantsDepositHelper extends Helper
{
    public function __construct(private ProductVariantsDepositsProviderInterface $productVariantsDepositProvider)
    {
    }

    /**
     * Get deposit price by given product variant and channel
     */
    public function getDeposit(ProductVariantInterface $productVariant, ChannelInterface $channel): ?int
    {
        return $productVariant->getDepositPriceByChannel($channel) ?? null;
    }

    /**
     * Get deposit prices by given product and channel
     */
    public function getDepositsByProduct(ProductInterface $product, ChannelInterface $channel): array
    {
        return $this->productVariantsDepositProvider->provideVariantsDeposits($product, $channel);
    }

    public function getDepositByOrderItem(OrderItemInterface $orderItem): ?int
    {
        if ($orderItem->getUnits()->count() === 0) {
            return null;
        }

        /** @var OrderItemUnit $firstUnit */
        $firstUnit = $orderItem->getUnits()->first();

        return $firstUnit->getAdjustmentsTotal(AdjustmentInterface::DEPOSIT_ADJUSTMENT);
    }

    public function getName(): string
    {
        return 'gewebe_product_variants_deposit';
    }
}
