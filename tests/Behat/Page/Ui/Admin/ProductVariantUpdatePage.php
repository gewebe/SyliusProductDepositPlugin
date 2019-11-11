<?php

declare(strict_types=1);

namespace Tests\Gweb\SyliusProductDepositPlugin\Behat\Page\Ui\Admin;

use Sylius\Behat\Page\Admin\ProductVariant\UpdatePage;

class ProductVariantUpdatePage extends UpdatePage implements ProductVariantUpdatePageInterface
{
    public function selectDepositTaxCategory(string $taxCategoryName): void
    {
        $this->getElement('deposit_tax_category')->selectOption($taxCategoryName);
    }

    public function getDepositTaxCategory(): ?string
    {
        return $this->getElement('deposit_tax_category')->getValue();
    }

    public function specifyDeposit(string $deposit, string $channelName): void
    {
        $this->getElement('deposit', ['%channelName%' => $channelName])->setValue($deposit);
    }

    public function getDepositForChannel(string $channelName): string
    {
        return $this->getElement('deposit', ['%channelName%' => $channelName])->getValue();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'deposit' => '#sylius_product_variant_channelDeposits > .field:contains("%channelName%") input[name$="[price]"]',
            'deposit_tax_category' => '#sylius_product_variant_depositTaxCategory'
        ]);
    }
}
