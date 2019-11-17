<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Provider;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface ProductVariantsDepositsProviderInterface
{
    public function provideVariantsDeposits(ProductInterface $product, ChannelInterface $channel): array;
}
