<?php

declare(strict_types=1);

namespace Gweb\SyliusDepositPlugin\Form\Extension;

use Gweb\SyliusDepositPlugin\Form\ChannelDepositType;
use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Extending the product variant form type and adding deposit
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
class ProductVariantTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $productVariant = $event->getData();

            $event->getForm()->add(
            'channelDeposits',
            ChannelCollectionType::class,
            [
              'entry_type' => ChannelDepositType::class,
              'entry_options' => function (ChannelInterface $channel) use ($productVariant) {
                  return [
                    'channel' => $channel,
                    'product_variant' => $productVariant,
                    'required' => false,
                  ];
              },
              'label' => 'gweb_deposit.admin.product_variant.price',
            ]
            );
          }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType(): string
    {
        return ProductVariantType::class;
    }
}
