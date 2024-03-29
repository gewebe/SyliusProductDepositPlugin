<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Provider;

use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductVariantsDepositsProvider implements ProductVariantsDepositsProviderInterface
{
    public function provideVariantsDeposits(ProductInterface $product, ChannelInterface $channel): array
    {
        $variantsDeposits = [];

        /** @var ProductVariantInterface $variant */
        foreach ($product->getVariants() as $variant) {
            $variantsDeposits[] = $this->constructOptionsMap($variant, $channel);
        }

        return $variantsDeposits;
    }

    private function constructOptionsMap(ProductVariantInterface $variant, ChannelInterface $channel): array
    {
        $optionMap = [];

        foreach ($variant->getOptionValues() as $option) {
            $optionMap[$option->getOptionCode() ?? ''] = $option->getCode();
        }

        $depositPrice = $variant->getDepositPriceByChannel($channel);
        if ($depositPrice !== null) {
            $optionMap['value'] = $depositPrice;
        }

        return $optionMap;
    }
}
