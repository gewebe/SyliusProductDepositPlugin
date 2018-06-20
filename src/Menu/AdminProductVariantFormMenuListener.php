<?php

declare(strict_types=1);

namespace Gweb\SyliusDepositPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
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

    public function addItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $menu->addChild('deposit', ['position' => 1])
          ->setAttribute(
            'template',
            '@GwebSyliusDepositPlugin/Resources/views/Admin/ProductVariant/Tab/_deposit.html.twig'
          )
          ->setLabel($this->translator->trans('gweb_deposit.admin.product_variant.menu'))
          ->setLabelAttribute('icon', 'dollar');
    }
}
