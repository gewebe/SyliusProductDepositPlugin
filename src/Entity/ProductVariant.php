<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gweb\SyliusProductDepositPlugin\Entity\ProductVariantInterface as DepositProductVariantInterface;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

/**
 * Entity for the product variant with deposits implemented as trait
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
class ProductVariant extends BaseProductVariant implements DepositProductVariantInterface
{
    use ProductVariantDepositTrait;

    public function __construct()
    {
        parent::__construct();

        $this->initProductVariantDepositTrait();
    }

}
