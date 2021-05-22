<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Behat\Page\Shop\Cart;

class SummaryPage extends \Sylius\Behat\Page\Shop\Cart\SummaryPage implements SummaryPageInterface
{
    public function getItemDepositPrice(string $productName): int
    {
        $unitDepositPrice = $this->getElement('product_deposit_price', ['%name%' => $productName]);

        return $this->getPriceFromString(trim($unitDepositPrice->getText()));
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'product_deposit_price' => '[data-test-cart-product-row="%name%"] [data-test-cart-product-deposit-price]',
        ]);
    }

    private function getPriceFromString(string $price): int
    {
        return (int) round((float) str_replace(['€', '£', '$'], '', $price) * 100, 2);
    }

}
