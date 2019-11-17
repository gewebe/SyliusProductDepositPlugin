<?php

declare(strict_types=1);

namespace Tests\Gweb\SyliusProductDepositPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\Gweb\SyliusProductDepositPlugin\Behat\Page\Shop\Product\ShowPageInterface;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    /**
     * @var ShowPageInterface
     */
    private $showPage;

    public function __construct(ShowPageInterface $showPage)
    {
        $this->showPage = $showPage;
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
