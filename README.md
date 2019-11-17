# Sylius Product Deposit Plugin
The deposit plugin in Sylius allows you to set an amount of refundable deposit for any product. For example: deposit for car replacement parts, batteries or bottle deposit for drinks.

## Features
 * Store individual deposit prices for each product variant and channel
 * The deposit price is displayed on the product detail page
 * At shopping cart / checkout / order the total unit price inclusive deposit is displayed.

## Installation

### Download the plugin via composer
```bash
composer require gweb/sylius-product-deposit-plugin
```

### Enable the plugin
Register the plugin by adding it to your `config/bundles.php` file

```php
<?php

return [
    // ...
    \Gweb\SyliusProductDepositPlugin\GwebSyliusProductDepositPlugin::class => ['all' => true],
    // ...
];
```

### Configure the plugin

```yaml
# config/packages/gweb_sylius_product_deposit.yaml

imports:
    - { resource: '@GwebSyliusProductDepositPlugin/Resources/config/app/config.yml'}
```

### Extend `ProductVariant` entity

- If you use `annotations` mapping:

    ```php
    <?php

    declare(strict_types=1);

    namespace App\Entity\Product;

    use Doctrine\ORM\Mapping as ORM;
    use Gweb\SyliusProductDepositPlugin\Entity\ProductVariantInterface as ProductVariantDepositInterface;
    use Gweb\SyliusProductDepositPlugin\Entity\ProductVariantDepositTrait;
    use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

    /**
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
    ```

- If you use `yaml` mapping:

    ```yaml
    App\Entity\ProductVariant:
        type: entity
        table: sylius_product_variant
        manyToOne:
            depositTaxCategory:
                targetEntity: Sylius\Component\Taxation\Model\TaxCategoryInterface
                joinColumn:
                    name: deposit_tax_category_id
                    referencedColumnName: id
                    onDelete: SET NULL
        oneToMany:
            channelDeposits:
                targetEntity: Gweb\SyliusProductDepositPlugin\Entity\ChannelDepositInterface
                mappedBy: productVariant
                orphanRemoval: true
                indexBy: channelCode
                cascade:
                    - all
    ```

### Update your database schema

```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```

## Usage

TODO
