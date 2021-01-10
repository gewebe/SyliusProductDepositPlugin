<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Form\Extension;

use Gewebe\SyliusProductDepositPlugin\Form\ChannelDepositType;
use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Extending the product variant form type and adding deposit
 */
class ProductVariantTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('depositTaxCategory', TaxCategoryChoiceType::class, [
            'required' => false,
            'placeholder' => '---',
            'label' => 'gewebe_sylius_product_deposit_plugin.admin.product_variant.tax_category',
        ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event): void {
                $productVariant = $event->getData();

                $event->getForm()->add(
                    'channelDeposits',
                    ChannelCollectionType::class,
                    [
                        'entry_type' => ChannelDepositType::class,
                        'entry_options' => function(ChannelInterface $channel) use ($productVariant): array {
                            return [
                                'channel' => $channel,
                                'product_variant' => $productVariant,
                                'required' => false,
                            ];
                        },
                        'label' => 'gewebe_sylius_product_deposit_plugin.admin.product_variant.price',
                    ]
                );
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): array
    {
        return [
            ProductVariantType::class
        ];
    }
}
