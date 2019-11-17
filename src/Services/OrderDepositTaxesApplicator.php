<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Services;

use Gweb\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
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
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
final class OrderDepositTaxesApplicator implements OrderTaxesApplicatorInterface
{
    /**
     * @var CalculatorInterface
     */
    private $calculator;

    /**
     * @var AdjustmentFactoryInterface
     */
    private $adjustmentFactory;

    /**
     * @var RepositoryInterface
     */
    private $taxRateRepository;

    /**
     * @param CalculatorInterface $calculator
     * @param AdjustmentFactoryInterface $adjustmentFactory
     * @param RepositoryInterface $taxRateRepository
     */
    public function __construct(
        CalculatorInterface $calculator,
        AdjustmentFactoryInterface $adjustmentFactory,
        RepositoryInterface $taxRateRepository
    ) {
        $this->calculator = $calculator;
        $this->adjustmentFactory = $adjustmentFactory;
        $this->taxRateRepository = $taxRateRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(OrderInterface $order, ZoneInterface $zone): void
    {
        /** @var OrderItemInterface $item */
        foreach ($order->getItems() as $item) {

            /** @var ProductVariantInterface $variant */
            $variant = $item->getVariant();

            $channelDeposit = $variant->getChannelDepositForChannel($order->getChannel());
            if (null == $channelDeposit) {
                continue;
            }

            $depositPrice = $channelDeposit->getPrice();

            $taxCategory = $variant->getDepositTaxCategory();

            /** @var TaxRate $taxRate */
            $taxRate = $this->taxRateRepository->findOneBy(['category' => $taxCategory, 'zone' => $zone]);
            if (null == $taxRate) {
                continue;
            }

            foreach ($item->getUnits() as $unit) {
                $taxAmount = $this->calculator->calculate($depositPrice, $taxRate);
                if (0.00 === $taxAmount) {
                    continue;
                }

                $this->addTaxAdjustment($unit, (int) $taxAmount, $taxRate->getLabel(), $taxRate->isIncludedInPrice());
            }
        }
    }

    /**
     * @param OrderItemUnitInterface $unit
     * @param int $taxAmount
     * @param string $label
     * @param bool $included
     */
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
