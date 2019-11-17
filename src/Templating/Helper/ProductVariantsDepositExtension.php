<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Templating\Helper;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Template extension to show product variant deposit price
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
final class ProductVariantsDepositExtension extends AbstractExtension
{
    /**
     * @var ProductVariantsDepositHelper
     */
    private $helproductVariantsDepositHelperer;

    /**
     * @param ProductVariantsDepositHelper $productVariantsDepositHelper
     */
    public function __construct(ProductVariantsDepositHelper $productVariantsDepositHelper)
    {
        $this->helproductVariantsDepositHelperer = $productVariantsDepositHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('gweb_calculate_deposit', [$this->helproductVariantsDepositHelperer, 'getDeposit']),
            new TwigFunction('gweb_product_variant_deposit', [$this->helproductVariantsDepositHelperer, 'getDepositsByProduct']),
        ];
    }
}
