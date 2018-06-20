<?php

declare(strict_types=1);

namespace Gweb\SyliusDepositPlugin\Templating\Helper;

use Gweb\SyliusDepositPlugin\Entity\ProductVariant;
use Symfony\Component\Templating\Helper\Helper;
use Webmozart\Assert\Assert;

/**
 * Template helper to get deposit price
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
class DepositHelper extends Helper
{
    /**
     * Get deposit price by given product variant and context
     * @param ProductVariant $productVariant
     * @param array $context
     * @return int|null
     */
    public function getPrice(ProductVariant $productVariant, array $context): ?int
    {
        Assert::keyExists($context, 'channel');

        if ($productVariant->hasChannelDepositForChannel($context['channel'])) {
            return $productVariant->getChannelDepositForChannel($context['channel'])->getPrice();
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'gweb_calculate_deposit';
    }
}
