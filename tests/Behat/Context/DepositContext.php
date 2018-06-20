<?php

namespace Gweb\Behat\Context;

use Behat\Behat\Context\Context;
use Gweb\SyliusDepositPlugin\Entity\ChannelDeposit;
use Gweb\SyliusDepositPlugin\Entity\ChannelDepositInterface;
use Gweb\SyliusDepositPlugin\Entity\ProductVariant;
use Sylius\Component\Core\Model\ProductInterface;

/**
 * Class DepositContext
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
final class DepositContext implements Context
{
    /**
     * @Given /^"([^"]*)" has a deposit fee with ("[^"]+")$/
     */
    public function productHasADepositPrice(ProductInterface $product, int $price): void
    {
        /** @var ProductVariant $productVariant */
        $productVariant = $product->getVariants()->toArray()[0];
        $deposit = $this->createDeposit($price);
        $productVariant->addChannelDeposit($deposit);
    }

    private function createDeposit($price): ChannelDepositInterface
    {
        $deposit = new ChannelDeposit();
        $deposit->setPrice((int)(floatval($price) * 100));
        return $deposit;
    }

}
