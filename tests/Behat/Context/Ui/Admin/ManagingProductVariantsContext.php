<?php

declare(strict_types=1);

namespace Tests\Gweb\SyliusProductDepositPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Gweb\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Tests\Gweb\SyliusProductDepositPlugin\Behat\Page\Ui\Admin\ProductVariantUpdatePageInterface;
use Webmozart\Assert\Assert;

final class ManagingProductVariantsContext implements Context
{
    /** @var ProductVariantUpdatePageInterface */
    private $updatePage;

    public function __construct(ProductVariantUpdatePageInterface $productVariantUpdatePage)
    {
        $this->updatePage = $productVariantUpdatePage;
    }

    /**
     * @When /^I set the deposit to "(?:€|£|\$)([^"]+)" for "([^"]+)" channel$/
     */
    public function iSetTheDepositTo(string $deposit, string $channel)
    {
        $this->updatePage->specifyDeposit($deposit, $channel);
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
     * @Then /^the (variant with code "[^"]+") should have a deposit with "(?:€|£|\$)([^"]+)" for channel "([^"]+)"$/
     */
    public function thisVariantShouldHaveADepositWith(ProductVariantInterface $productVariant, string $deposit, string $channel)
    {
        $this->updatePage->open(['id' => $productVariant->getId(), 'productId' => $productVariant->getProduct()->getId()]);

        Assert::eq($deposit, $this->updatePage->getDepositForChannel($channel));
    }
}
