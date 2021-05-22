<?php

declare(strict_types=1);

namespace spec\Gewebe\SyliusProductDepositPlugin\Taxation;

use Doctrine\Common\Collections\ArrayCollection;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Gewebe\SyliusProductDepositPlugin\Taxation\OrderDepositTaxesApplicator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Component\Core\Model\TaxRateInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

final class OrderDepositTaxesApplicatorSpec extends ObjectBehavior
{
    function let(
        AdjustmentFactoryInterface $adjustmentFactory,
        CalculatorInterface $calculator,
        RepositoryInterface $taxRateRepository
    ) {
        $this->beConstructedWith($adjustmentFactory, $calculator, $taxRateRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderDepositTaxesApplicator::class);
    }

    function it_implements_order_processor_interface(): void
    {
        $this->shouldImplement(OrderTaxesApplicatorInterface::class);
    }

    function it_apply_taxes(
        AdjustmentFactoryInterface $adjustmentFactory,
        AdjustmentInterface $adjustment,
        CalculatorInterface $calculator,
        OrderInterface $order,
        OrderItemInterface $orderItem,
        OrderItemUnitInterface $orderItemUnit,
        ProductVariantInterface $productVariant,
        RepositoryInterface $taxRateRepository,
        TaxCategoryInterface $taxCategory,
        TaxRateInterface $taxRate,
        ZoneInterface $zone
    ): void {
        $adjustmentFactory->createWithData(
            AdjustmentInterface::TAX_ADJUSTMENT,
            'Deposit Tax (3%)',
            15,
            false,
            [
                'taxRateCode' => 'deposit',
                'taxRateName' => 'Deposit Tax',
                'taxRateAmount' => 0.03,
            ]
        )->willReturn($adjustment);

        $taxRate->getLabel()->willReturn('Deposit Tax (3%)');
        $taxRate->getName()->willReturn('Deposit Tax');
        $taxRate->getCode()->willReturn('deposit');
        $taxRate->getAmount()->willReturn(0.03);
        $taxRate->isIncludedInPrice()->willReturn(false);

        $calculator->calculate(100, $taxRate)->willReturn(15);
        $taxRateRepository->findOneBy(['category' => $taxCategory, 'zone' => $zone])->willReturn($taxRate);
        $this->beConstructedWith($adjustmentFactory, $calculator, $taxRateRepository);

        $productVariant->getDepositTaxCategory()->willReturn($taxCategory);

        $orderItem->getVariant()->willReturn($productVariant);
        $orderItem->getUnits()->willReturn(new ArrayCollection([$orderItemUnit->getWrappedObject()]));

        $orderItemUnit->getAdjustmentsTotal('deposit')->willReturn(100);
        $orderItemUnit->addAdjustment($adjustment)->shouldBeCalled();

        $order->getItems()->willReturn(new ArrayCollection([$orderItem->getWrappedObject()]));


        $this->apply($order, $zone);
    }
}
