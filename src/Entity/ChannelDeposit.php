<?php

declare(strict_types=1);

namespace Gweb\SyliusDepositPlugin\Entity;

use Sylius\Component\Core\Model\ProductVariantInterface;

/**
 * Entity that stores deposit for product variants
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
class ChannelDeposit implements ChannelDepositInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $price;

    /**
     * @var string
     */
    private $channelCode;

    /**
     * @var ProductVariantInterface
     */
    private $productVariant;

    /**
     * {@inheritdoc}
     */
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

    /**
     * @return int
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return ProductVariantInterface
     */
    public function getProductVariant(): ProductVariantInterface
    {
        return $this->productVariant;
    }

    /**
     * @param ProductVariantInterface $productVariants
     */
    public function setProductVariant(ProductVariantInterface $productVariants): void
    {
        $this->productVariant = $productVariants;
    }

    /**
     * @return null|string
     */
    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    /**
     * @param null|string $channelCode
     */
    public function setChannelCode(?string $channelCode): void
    {
        $this->channelCode = $channelCode;
    }

}
