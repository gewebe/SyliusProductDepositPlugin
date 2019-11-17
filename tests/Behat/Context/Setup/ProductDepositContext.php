<?php

declare(strict_types=1);

namespace Tests\Gweb\SyliusProductDepositPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Gweb\SyliusProductDepositPlugin\Entity\ChannelDeposit;
use Gweb\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

final class ProductDepositContext implements Context
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @Given /^(this variant) has a deposit priced at ("[^"]+") in ("([^"]+)" channel)$/
     */
    public function thisVariantHasDepositPricedAtInChannel(ProductVariantInterface $productVariant, int $price, ChannelInterface $channel)
    {
        $channelDeposit = new ChannelDeposit();
        $channelDeposit->setPrice($price);
        $channelDeposit->setChannelCode($channel->getCode());

        $productVariant->addChannelDeposit($channelDeposit);

        $this->objectManager->flush();
    }

    /**
     * @Given /^(this variant) has a deposit tax category with code ("[^"]+")$/
     */
    public function thisVariantHasDepositTaxCategoryWithCode(ProductVariantInterface $productVariant, TaxCategoryInterface $taxCategory)
    {
        $productVariant->setDepositTaxCategory($taxCategory);

        $this->objectManager->flush();
    }
}
