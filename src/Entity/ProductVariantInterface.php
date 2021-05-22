<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;
use \Sylius\Component\Core\Model\ProductVariantInterface as BaseProductVariantInterface;

/**
 * Entity for the product variant with deposits
 */
interface ProductVariantInterface extends BaseProductVariantInterface
{
    /**
     * Returns all deposit elements for all channels
     * @psalm-return Collection<array-key, ChannelDepositInterface>
     */
    public function getChannelDeposits(): Collection;

    public function getDepositPriceByChannel(ChannelInterface $channel): ?int;

    public function hasChannelDeposit(ChannelDepositInterface $channelDeposit): bool;

    public function addChannelDeposit(ChannelDepositInterface $channelDeposit): void;

    public function removeChannelDeposit(ChannelDepositInterface $channelDeposit): void;

    public function getDepositTaxCategory(): ?TaxCategoryInterface;

    public function setDepositTaxCategory(TaxCategoryInterface $depositTaxCategory): void;
}
