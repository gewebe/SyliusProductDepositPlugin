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
     * @return Collection|ChannelDepositInterface[]
     *
     * @psalm-return Collection<array-key, ChannelDepositInterface>
     */
    public function getChannelDeposits(): Collection;

    /**
     * Returns the deposit element for one channel
     * @param ChannelInterface $channel
     * @return ChannelDepositInterface|null
     */
    public function getChannelDepositForChannel(ChannelInterface $channel): ?ChannelDepositInterface;

    /**
     * Check if channel has deposit element
     * @param ChannelInterface $channel
     * @return bool
     */
    public function hasChannelDepositForChannel(ChannelInterface $channel): bool;

    /**
     * Check if has deposit element
     * @param ChannelDepositInterface $channelDeposit
     * @return bool
     */
    public function hasChannelDeposit(ChannelDepositInterface $channelDeposit): bool;

    /**
     * Adds an deposit element to the list
     * @param ChannelDepositInterface $channelDeposit
     * @return void
     */
    public function addChannelDeposit(ChannelDepositInterface $channelDeposit): void;

    /**
     * Removes a deposit element from the list
     * @param ChannelDepositInterface $channelDeposit
     * @return void
     */
    public function removeChannelDeposit(ChannelDepositInterface $channelDeposit): void;

    /**
     * @return TaxCategoryInterface
     */
    public function getDepositTaxCategory(): ?TaxCategoryInterface;

    /**
     * @param TaxCategoryInterface $depositTaxCategory
     */
    public function setDepositTaxCategory(TaxCategoryInterface $depositTaxCategory): void;
}
