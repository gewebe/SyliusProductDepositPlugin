<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Taxation;

use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\TaxRate;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Model\OrderItemUnitInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;

/**
 * Apply deposit tax to order item units
 */
final class OrderDepositTaxesApplicator implements OrderTaxesApplicatorInterface
{
    /** @var CalculatorInterface */
    private $calculator;

    /** @var AdjustmentFactoryInterface */
    private $adjustmentFactory;

    /** @var RepositoryInterface */
    private $taxRateRepository;

    public function __construct(
        CalculatorInterface $calculator,
        AdjustmentFactoryInterface $adjustmentFactory,
        RepositoryInterface $taxRateRepository
    ) {
        $this->calculator = $calculator;
        $this->adjustmentFactory = $adjustmentFactory;
        $this->taxRateRepository = $taxRateRepository;
    }

    public function apply(OrderInterface $order, ZoneInterface $zone): void
    {
        /** @var OrderItemInterface $item */
        foreach ($order->getItems() as $item) {

            /** @var ProductVariantInterface $variant */
            $variant = $item->getVariant();

            $channel = $order->getChannel();
            if (null == $channel) {
                continue;
            }

            $channelDeposit = $variant->getChannelDepositForChannel($channel);
            if (null == $channelDeposit) {
                continue;
            }

            $taxCategory = $variant->getDepositTaxCategory();

            /** @var TaxRate|null $taxRate */
            $taxRate = $this->taxRateRepository->findOneBy(['category' => $taxCategory, 'zone' => $zone]);
            if (null == $taxRate) {
                continue;
            }

            foreach ($item->getUnits() as $unit) {
                $taxAmount = $this->calculator->calculate((float) $channelDeposit->getPrice(), $taxRate);
                if (0.00 === $taxAmount) {
                    continue;
                }

                $this->addTaxAdjustment(
                    $unit,
                    (int) $taxAmount,
                    (string) $taxRate->getLabel(),
                    $taxRate->isIncludedInPrice()
                );
            }
        }
    }

    private function addTaxAdjustment(OrderItemUnitInterface $unit, int $taxAmount, string $label, bool $included): void
    {
        $unitTaxAdjustment = $this->adjustmentFactory->createWithData(
            AdjustmentInterface::TAX_ADJUSTMENT,
            $label,
            $taxAmount,
            $included
        );
        $unit->addAdjustment($unitTaxAdjustment);
    }
}
