<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Behat\Page\Shop\Cart;

interface SummaryPageInterface extends \Sylius\Behat\Page\Shop\Cart\SummaryPageInterface
{
    public function getItemDepositPrice(string $productName): int;
}
