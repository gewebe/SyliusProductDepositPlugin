<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Entity;

/**
 * Entity that stores deposit for product variants
 */
class ChannelDeposit implements ChannelDepositInterface
{
    /** @var mixed */
    private $id;

    /** @var int|null */
    private $price;

    /** @var string|null */
    private $channelCode;

    /** @var ProductVariantInterface|null */
    private $productVariant;

    public function __toString(): string
    {
        return (string) $this->getPrice();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function getProductVariant(): ?ProductVariantInterface
    {
        return $this->productVariant;
    }

    public function setProductVariant(?ProductVariantInterface $productVariants): void
    {
        $this->productVariant = $productVariants;
    }

    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    public function setChannelCode(?string $channelCode): void
    {
        $this->channelCode = $channelCode;
    }
}
