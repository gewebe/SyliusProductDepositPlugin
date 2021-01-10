<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Templating\Helper;

use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Gewebe\SyliusProductDepositPlugin\Provider\ProductVariantsDepositsProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * Template helper to get product variant deposit prices
 */
class ProductVariantsDepositHelper extends Helper
{
    /**
     * @var ProductVariantsDepositsProviderInterface
     */
    private $productVariantsDepositProvider;

    public function __construct(ProductVariantsDepositsProviderInterface $productVariantsDepositProvider)
    {
        $this->productVariantsDepositProvider = $productVariantsDepositProvider;
    }

    /**
     * Get deposit price by given product variant and channel
     * @param ProductVariantInterface $productVariant
     * @param ChannelInterface $channel
     * @return int|null
     */
    public function getDeposit(ProductVariantInterface $productVariant, ChannelInterface $channel): ?int
    {
        $channelDeposit = $productVariant->getChannelDepositForChannel($channel);
        if (null != $channelDeposit) {
            return $channelDeposit->getPrice();
        }
        return null;
    }

    /**
     * Get deposit prices by given product and channel
     * @param ProductInterface $product
     * @param ChannelInterface $channel
     * @return array
     */
    public function getDepositsByProduct(ProductInterface $product, ChannelInterface $channel): array
    {
        return $this->productVariantsDepositProvider->provideVariantsDeposits($product, $channel);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'gewebe_product_variants_deposit';
    }
}
