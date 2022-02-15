<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Tests\Gewebe\SyliusProductDepositPlugin\Behat\Page\Admin\ProductVariantUpdatePageInterface;
use Webmozart\Assert\Assert;

final class ManagingProductVariantsContext implements Context
{
    public function __construct(private ProductVariantUpdatePageInterface $updatePage)
    {
    }

    /**
     * @When /^I set the deposit price to "(?:€|£|\$)([^"]+)" for "([^"]+)" channel$/
     */
    public function iSetTheDepositTo(string $deposit, string $channel)
    {
        $this->updatePage->specifyDepositPrice($deposit, $channel);
    }

    /**
     * @When /^I set the deposit tax category to "([^"]+)"$/
     */
    public function iSetTheDepositTaxTo(string $taxCategoryName)
    {
        $this->updatePage->selectDepositTaxCategory($taxCategoryName);
    }

    /**
     * @Then /^this variant should have a deposit tax category code "([^"]+)"$/
     */
    public function thisVariantShouldHaveADepositTaxCategory(string $taxCategoryName)
    {
        Assert::eq($taxCategoryName, $this->updatePage->getDepositTaxCategory());
    }

    /**
     * @Then /^the (variant with code "[^"]+") should have a deposit price with "(?:€|£|\$)([^"]+)" for channel "([^"]+)"$/
     */
    public function thisVariantShouldHaveADepositWith(ProductVariantInterface $productVariant, string $price, string $channel)
    {
        $this->updatePage->open(['id' => $productVariant->getId(), 'productId' => $productVariant->getProduct()->getId()]);

        Assert::eq($price, $this->updatePage->getDepositPriceForChannel($channel));
    }
}
