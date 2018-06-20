<?php

declare(strict_types=1);

namespace Gweb\SyliusDepositPlugin\Entity;

use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

/**
 * Entity for the product variant with deposits implemented as trait.
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
class ProductVariant extends BaseProductVariant
{
    use ChannelDepositTrait;

    public function __construct()
    {
        parent::__construct();

        $this->initChannelDepositTrait();
    }

}
