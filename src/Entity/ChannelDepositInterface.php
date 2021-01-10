<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Interface for product variant deposit
 */
interface ChannelDepositInterface extends ResourceInterface
{
    /**
     * @return int
     */
    public function getPrice(): ?int;

    /**
     * @param int $price
     */
    public function setPrice(int $price): void;

    /**
     * @return ProductVariantInterface|null
     */
    public function getProductVariant(): ?ProductVariantInterface;

    /**
     * @param ProductVariantInterface $productVariants
     */
    public function setProductVariant(?ProductVariantInterface $productVariants): void;

    /**
     * @return string
     */
    public function getChannelCode(): ?string;

    /**
     * @param string $channelCode
     */
    public function setChannelCode(string $channelCode): void;
}
