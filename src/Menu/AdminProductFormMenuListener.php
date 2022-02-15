<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Menu;

use Sylius\Bundle\AdminBundle\Event\ProductMenuBuilderEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Adding the deposit point to the product menu
 */
final class AdminProductFormMenuListener
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function addItems(ProductMenuBuilderEvent $event): void
    {
        if (!$event->getProduct()->isSimple()) {
            return;
        }

        $menu = $event->getMenu();

        $menu->addChild('deposit', ['position' => 1])
            ->setAttribute(
                'template',
                '@GewebeSyliusProductDepositPlugin/Admin/Product/Tab/_deposit.html.twig'
            )
            ->setLabel($this->translator->trans('gewebe_sylius_product_deposit_plugin.admin.product_variant.menu'))
            ->setLabelAttribute('icon', 'dollar');
    }
}
