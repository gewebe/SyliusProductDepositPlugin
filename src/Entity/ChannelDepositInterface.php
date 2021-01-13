<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Interface for product variant deposit
 */
interface ChannelDepositInterface extends ResourceInterface
{
    public function getPrice(): ?int;

    public function setPrice(int $price): void;

    public function getProductVariant(): ?ProductVariantInterface;

    public function setProductVariant(?ProductVariantInterface $productVariants): void;

    public function getChannelCode(): ?string;

    public function setChannelCode(string $channelCode): void;
}
