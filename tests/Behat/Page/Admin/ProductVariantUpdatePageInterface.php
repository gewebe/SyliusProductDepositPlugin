<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Behat\Page\Admin;

interface ProductVariantUpdatePageInterface
{
    public function selectDepositTaxCategory(string $taxCategoryName): void;

    public function getDepositTaxCategory(): ?string;

    public function specifyDepositPrice(string $price, string $channelName): void;

    public function getDepositPriceForChannel(string $channelName): string;
}
