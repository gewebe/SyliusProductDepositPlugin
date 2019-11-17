<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Menu;

use Sylius\Bundle\AdminBundle\Event\ProductVariantMenuBuilderEvent;
use Symfony\Component\Translation\Translator;

/**
 * Adding the deposit point to the product variant menu
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
final class AdminProductVariantFormMenuListener
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function addItems(ProductVariantMenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu->addChild('deposit', ['position' => 1])
            ->setAttribute(
                'template',
                '@GwebSyliusProductDepositPlugin/Resources/views/Admin/ProductVariant/Tab/_deposit.html.twig'
            )
            ->setLabel($this->translator->trans('gweb_deposit.admin.product_variant.menu'))
            ->setLabelAttribute('icon', 'dollar');
    }
}
