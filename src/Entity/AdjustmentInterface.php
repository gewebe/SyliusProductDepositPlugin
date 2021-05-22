<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Entity;

use \Sylius\Component\Core\Model\AdjustmentInterface as BaseAdjustmentInterface;

interface AdjustmentInterface extends BaseAdjustmentInterface
{
    public const DEPOSIT_ADJUSTMENT = 'deposit';
}
