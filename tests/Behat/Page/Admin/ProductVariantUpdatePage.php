<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Behat\Page\Admin;

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

    public function specifyDepositPrice(string $price, string $channelName): void
    {
        $this->getElement('deposit', ['%channelName%' => $channelName])->setValue($price);
    }

    public function getDepositPriceForChannel(string $channelName): string
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
