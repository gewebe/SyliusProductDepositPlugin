<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\Form;

use Gewebe\SyliusProductDepositPlugin\Entity\ChannelDepositInterface;
use Gewebe\SyliusProductDepositPlugin\Entity\ProductVariantInterface;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for the deposit entity
 */
final class ChannelDepositType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ChannelInterface $channel */
        $channel = $options['channel'];
        $baseCurrency = $channel->getBaseCurrency();
        if ($baseCurrency === null) {
            return;
        }

        $builder->add(
            'price',
            MoneyType::class,
            [
                'label' => 'gewebe_sylius_product_deposit_plugin.admin.product_variant.price',
                'required' => false,
                'currency' => $baseCurrency->getCode(),
            ]
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function(FormEvent $event) use ($options): void {
                /** @var ChannelDepositInterface $channelDeposit */
                $channelDeposit = $event->getData();

                if (!$channelDeposit instanceof $this->dataClass) {
                    $event->setData(null);

                    return;
                }

                /** @var ChannelInterface $channel */
                $channel = $options['channel'];
                $channelCode = $channel->getCode();
                if ($channelCode === null) {
                    $event->setData(null);

                    return;
                }

                /** @var ProductVariantInterface $productVariant */
                $productVariant = $options['product_variant'];

                $channelDeposit->setChannelCode($channelCode);
                $channelDeposit->setProductVariant($productVariant);

                $event->setData($channelDeposit);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('channel')
            ->setAllowedTypes('channel', [ChannelInterface::class])
            ->setDefined('product_variant')
            ->setAllowedTypes('product_variant', ['null', ProductVariantInterface::class])
            ->setDefaults(
                [
                    'label' => function(Options $options): string {
                        /** @var ChannelInterface $channel */
                        $channel = $options['channel'];
                        return $channel->getName() ?? '';
                    },
                ]
            );
    }

    public function getBlockPrefix(): string
    {
        return 'gewebe_channel_deposit';
    }
}
