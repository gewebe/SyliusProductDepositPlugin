<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

/**
 * Trait that implements the deposit functionality
 * Used in:
 * <li>@see ProductVariant</li>
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
trait ProductVariantDepositTrait
{
    public function initProductVariantDepositTrait()
    {
        $this->channelDeposits = new ArrayCollection();
    }

    /**
     * @var Collection
     */
    protected $channelDeposits;

    /**
     * @var TaxCategoryInterface
     */
    protected $depositTaxCategory;

    /**
     * Returns all deposit elements for all channels
     * @return Collection
     */
    public function getChannelDeposits(): Collection
    {
        return $this->channelDeposits;
    }

    /**
     * Returns the deposit element for one channel
     * @param ChannelInterface $channel
     * @return ChannelDepositInterface|null
     */
    public function getChannelDepositForChannel(ChannelInterface $channel): ?ChannelDepositInterface
    {
        if ($this->channelDeposits->containsKey($channel->getCode())) {
            return $this->channelDeposits->get($channel->getCode());
        }

        return null;
    }

    /**
     * Check if channel has deposit element
     * @param ChannelInterface $channel
     * @return bool
     */
    public function hasChannelDepositForChannel(ChannelInterface $channel): bool
    {
        return null !== $this->getChannelDepositForChannel($channel);
    }

    /**
     * Check if has deposit element
     * @param ChannelDepositInterface $channelDeposit
     * @return bool
     */
    public function hasChannelDeposit(ChannelDepositInterface $channelDeposit): bool
    {
        return $this->channelDeposits->contains($channelDeposit);
    }

    /**
     * Adds an deposit element to the list
     * @param ChannelDepositInterface $channelDeposit
     * @return void
     */
    public function addChannelDeposit(ChannelDepositInterface $channelDeposit): void
    {
        if (!$this->hasChannelDeposit($channelDeposit)) {
            $channelDeposit->setProductVariant($this);
            $this->channelDeposits->set($channelDeposit->getChannelCode(), $channelDeposit);
        }
    }

    /**
     * Removes a deposit element from the list
     * @param ChannelDepositInterface $channelDeposit
     * @return void
     */
    public function removeChannelDeposit(ChannelDepositInterface $channelDeposit): void
    {
        if ($this->hasChannelDeposit($channelDeposit)) {
            $channelDeposit->setProductVariant(null);
            $this->channelDeposits->remove($channelDeposit->getChannelCode());
        }
    }

    /**
     * @return TaxCategoryInterface
     */
    public function getDepositTaxCategory(): ?TaxCategoryInterface
    {
        return $this->depositTaxCategory;
    }

    /**
     * @param TaxCategoryInterface $depositTaxCategory
     */
    public function setDepositTaxCategory(?TaxCategoryInterface $depositTaxCategory): void
    {
        $this->depositTaxCategory = $depositTaxCategory;
    }

}
