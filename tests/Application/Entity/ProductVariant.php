<?php

declare(strict_types=1);

namespace Tests\Gewebe\SyliusProductDepositPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface as ProductVariantDepositInterface;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantDepositTrait;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

/**
 * Product variant entity with deposit price
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant")
 */
class ProductVariant extends BaseProductVariant implements ProductVariantDepositInterface
{
    use ProductVariantDepositTrait;

    public function __construct()
    {
        parent::__construct();

        $this->initProductVariantDepositTrait();
    }
}
