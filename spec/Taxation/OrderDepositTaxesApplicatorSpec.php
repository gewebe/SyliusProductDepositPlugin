<?php

declare(strict_types=1);

namespace spec\Gweb\SyliusProductDepositPlugin\Taxation;

use Doctrine\Common\Collections\ArrayCollection;
use Gweb\SyliusProductDepositPlugin\Entity\ChannelDepositInterface;
use Gweb\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Gweb\SyliusProductDepositPlugin\Taxation\OrderDepositTaxesApplicator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\ChannelInterface;
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
        CalculatorInterface $calculator,
        AdjustmentFactoryInterface $adjustmentFactory,
        RepositoryInterface $taxRateRepository
    ) {
        $this->beConstructedWith($calculator, $adjustmentFactory, $taxRateRepository);
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
        ChannelInterface $channel,
        ChannelDepositInterface $channelDeposit,
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
            'Returnable tax (exclude)',
            15,
            false
        )->willReturn($adjustment);

        $taxRate->getLabel()->willReturn('Returnable tax (exclude)');
        $taxRate->isIncludedInPrice()->willReturn(false);

        $calculator->calculate(100, $taxRate)->willReturn(15);
        $taxRateRepository->findOneBy(['category' => $taxCategory, 'zone' => $zone])->willReturn($taxRate);
        $this->beConstructedWith($calculator, $adjustmentFactory, $taxRateRepository);

        $channelDeposit->getPrice()->willReturn(100);

        $productVariant->getChannelDepositForChannel($channel)->willReturn($channelDeposit);
        $productVariant->getDepositTaxCategory()->willReturn($taxCategory);

        $orderItem->getVariant()->willReturn($productVariant);
        $orderItem->getUnits()->willReturn(new ArrayCollection([$orderItemUnit->getWrappedObject()]));

        $orderItemUnit->addAdjustment($adjustment)->shouldBeCalled();

        $order->getChannel()->willReturn($channel);
        $order->getItems()->willReturn(new ArrayCollection([$orderItem->getWrappedObject()]));


        $this->apply($order, $zone);
    }
}
