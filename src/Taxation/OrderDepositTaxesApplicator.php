<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Taxation;

use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\TaxRateInterface;
use Sylius\Component\Order\Model\OrderItemUnitInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;

/**
 * Apply deposit tax adjustment to order item units
 */
final class OrderDepositTaxesApplicator implements OrderTaxesApplicatorInterface
{
    /** @var AdjustmentFactoryInterface */
    private $adjustmentFactory;

    /** @var CalculatorInterface */
    private $calculator;

    /** @var RepositoryInterface */
    private $taxRateRepository;

    public function __construct(
        AdjustmentFactoryInterface $adjustmentFactory,
        CalculatorInterface $calculator,
        RepositoryInterface $taxRateRepository
    ) {
        $this->adjustmentFactory = $adjustmentFactory;
        $this->calculator = $calculator;
        $this->taxRateRepository = $taxRateRepository;
    }

    public function apply(OrderInterface $order, ZoneInterface $zone): void
    {
        foreach ($order->getItems() as $item) {
            /** @var ProductVariantInterface $variant */
            $variant = $item->getVariant();

            $taxCategory = $variant->getDepositTaxCategory();

            /** @var TaxRateInterface|null $taxRate */
            $taxRate = $this->taxRateRepository->findOneBy(['category' => $taxCategory, 'zone' => $zone]);
            if (null == $taxRate) {
                continue;
            }

            foreach ($item->getUnits() as $unit) {
                $depositPrice = $unit->getAdjustmentsTotal('deposit');

                $taxAmount = $this->calculator->calculate((float) $depositPrice, $taxRate);
                if (0.00 === $taxAmount) {
                    continue;
                }

                $this->addAdjustment(
                    $unit,
                    $taxRate,
                    (int) $taxAmount
                );
            }
        }
    }

    private function addAdjustment(OrderItemUnitInterface $unit, TaxRateInterface $taxRate, int $taxAmount): void
    {
        $unitTaxAdjustment = $this->adjustmentFactory->createWithData(
            AdjustmentInterface::TAX_ADJUSTMENT,
            (string) $taxRate->getLabel(),
            $taxAmount,
            $taxRate->isIncludedInPrice(),
            [
                'taxRateCode' => $taxRate->getCode(),
                'taxRateName' => $taxRate->getName(),
                'taxRateAmount' => $taxRate->getAmount(),
            ]
        );
        $unit->addAdjustment($unitTaxAdjustment);
    }
}
