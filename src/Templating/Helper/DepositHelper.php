<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Templating\Helper;

use Gweb\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Symfony\Component\Templating\Helper\Helper;
use Webmozart\Assert\Assert;

/**
 * Template helper to get deposit fee
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
class DepositHelper extends Helper
{
    /**
     * Get deposit fee by given product variant and context
     * @param ProductVariantInterface $productVariant
     * @param array $context
     * @return int|null
     */
    public function getPrice(ProductVariantInterface $productVariant, array $context): ?int
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
