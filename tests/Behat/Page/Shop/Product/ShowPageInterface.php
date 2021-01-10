<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Behat\Page\Shop\Product;

interface ShowPageInterface extends \Sylius\Behat\Page\Shop\Product\ShowPageInterface
{
    public function getDepositPrice();
}
