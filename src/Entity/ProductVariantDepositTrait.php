<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

/**
 * Trait that implements the deposit functionality
 * Used in:
 * <li>@see ProductVariant</li>
 */
trait ProductVariantDepositTrait
{
    public function initProductVariantDepositTrait(): void
    {
        $this->channelDeposits = new ArrayCollection();
    }

    /**
     * @var Collection
     *
     * @psalm-var Collection<array-key, ChannelDepositInterface>
     *
     * @ORM\OneToMany(
     *     targetEntity="\Gewebe\SyliusProductDepositPlugin\Entity\ChannelDepositInterface",
     *     mappedBy="productVariant",
     *     indexBy="channelCode",
     *     cascade={"all"},
     *     orphanRemoval=true
     * )
     */
    protected $channelDeposits;

    /**
     * @var TaxCategoryInterface|null
     *
     * @ORM\ManyToOne(targetEntity="\Sylius\Component\Taxation\Model\TaxCategoryInterface", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="deposit_tax_category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $depositTaxCategory;

    /**
     * Returns all deposit elements for all channels
     * @psalm-return Collection<array-key, ChannelDepositInterface>
     */
    public function getChannelDeposits(): Collection
    {
        return $this->channelDeposits;
    }

    public function getDepositPriceByChannel(ChannelInterface $channel): ?int
    {
        $channelCode = $channel->getCode();
        if ($channelCode === null) {
            return null;
        }

        $channelDeposit = $this->channelDeposits->get($channelCode);
        if (!$channelDeposit instanceof ChannelDepositInterface) {
            return null;
        }

        return $channelDeposit->getPrice() ?? null;
    }

    public function hasChannelDeposit(ChannelDepositInterface $channelDeposit): bool
    {
        return $this->channelDeposits->contains($channelDeposit);
    }

    public function addChannelDeposit(ChannelDepositInterface $channelDeposit): void
    {
        $channelCode = $channelDeposit->getChannelCode();

        if ($channelCode !== null && !$this->hasChannelDeposit($channelDeposit)) {
            $channelDeposit->setProductVariant($this);
            $this->channelDeposits->set($channelCode, $channelDeposit);
        }
    }

    public function removeChannelDeposit(ChannelDepositInterface $channelDeposit): void
    {
        $channelCode = $channelDeposit->getChannelCode();

        if ($channelCode !== null && $this->hasChannelDeposit($channelDeposit)) {
            $channelDeposit->setProductVariant(null);
            $this->channelDeposits->remove($channelCode);
        }
    }

    public function getDepositTaxCategory(): ?TaxCategoryInterface
    {
        return $this->depositTaxCategory;
    }

    public function setDepositTaxCategory(TaxCategoryInterface $depositTaxCategory): void
    {
        $this->depositTaxCategory = $depositTaxCategory;
    }
}
