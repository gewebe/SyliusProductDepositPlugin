<?php

declare(strict_types=1);

namespace Tests\Gweb\SyliusProductDepositPlugin\Behat\Page\Ui\Admin;

interface ProductVariantUpdatePageInterface
{
    public function selectDepositTaxCategory(string $taxCategoryName): void;

    public function getDepositTaxCategory(): ?string;

    public function specifyDeposit(string $deposit, string $channelName): void;

    public function getDepositForChannel(string $channelName): string;
}
