<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Form;

use Gweb\SyliusProductDepositPlugin\Entity\ChannelDepositInterface;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for the deposit entity
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
final class ChannelDepositType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'price',
            MoneyType::class,
            [
                'label' => 'gweb_deposit.admin.product_variant.price',
                'required' => false,
                'currency' => $options['channel']->getBaseCurrency()->getCode(),
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

                $channelDeposit->setChannelCode($options['channel']->getCode());
                $channelDeposit->setProductVariant($options['product_variant']);

                $event->setData($channelDeposit);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
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
                        return $options['channel']->getName();
                    },
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'gweb_channel_deposit';
    }
}
