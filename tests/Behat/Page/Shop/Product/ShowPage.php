<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Behat\Page\Shop\Product;

class ShowPage extends \Sylius\Behat\Page\Shop\Product\ShowPage implements ShowPageInterface
{
    public function getDepositPrice(): string
    {
        return $this->getElement('product_deposit')->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'product_deposit' => '#product-deposit .price',
        ]);
    }
}
