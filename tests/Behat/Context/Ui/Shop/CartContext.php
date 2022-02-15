<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\Gewebe\SyliusProductDepositPlugin\Behat\Page\Shop\Cart\SummaryPageInterface;
use Webmozart\Assert\Assert;

final class CartContext implements Context
{
    public function __construct(private SummaryPageInterface $summaryPage)
    {
    }

    /**
     * @Then /^I should see "([^"]+)" with deposit price ("[^"]+") in my cart$/
     */
    public function iShouldSeeProductWithDepositPriceInMyCart($productName, $unitPrice)
    {
        Assert::same($this->summaryPage->getItemDepositPrice($productName), $unitPrice);
    }
}
