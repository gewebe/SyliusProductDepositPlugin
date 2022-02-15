<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Templating\Helper;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Template extension to show product variant deposit price
 */
final class ProductVariantsDepositExtension extends AbstractExtension
{
    public function __construct(private ProductVariantsDepositHelper $productVariantsDepositHelper)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('gewebe_calculate_deposit', [$this->productVariantsDepositHelper, 'getDeposit']),
            new TwigFunction('gewebe_product_variant_deposit', [$this->productVariantsDepositHelper, 'getDepositsByProduct']),
            new TwigFunction('gewebe_order_item_deposit', [$this->productVariantsDepositHelper, 'getDepositByOrderItem']),
        ];
    }
}
