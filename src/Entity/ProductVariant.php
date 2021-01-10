<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface as DepositProductVariantInterface;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

/**
 * Entity for the product variant with deposits implemented as trait
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant")
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
