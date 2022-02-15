<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\Gewebe\SyliusProductDepositPlugin\Behat\Page\Shop\Product\ShowPageInterface;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    public function __construct(private ShowPageInterface $showPage)
    {
    }

    /**
     * @Then /^the product deposit price should be "([^"]+)"$/
     * @Then /^I should see the product deposit price "([^"]+)"$/
     */
    public function iShouldSeeTheProductPrice(string $price)
    {
        Assert::same($this->showPage->getDepositPrice(), $price);
    }
}
