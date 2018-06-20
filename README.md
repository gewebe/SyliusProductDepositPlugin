# Sylius Deposit Plugin
The deposit plugin in Sylius allows you to set an amount of refundable deposit for any product. For example: deposit for car replacement parts, batteries or bottle deposit for drinks.

## Features

 * Store individual deposit fees for each product variant and channel
 * The deposit is displayed on the product detail page
 * At shopping cart / checkout / order the total unit price inclusive deposit is displayed.

## Installation

Install the plugin via composer
```bash
composer require gweb/sylius-deposit-plugin
```

Register the plugin in your AppKernel file:
```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...

        new \Gweb\SyliusDepositPlugin\GwebSyliusDepositPlugin(),
    ]);
}
```

Add the `config.yml` to your local `app/config/config.yml`
```yml
imports:
    - { resource: '@GwebSyliusDepositPlugin/Resources/config/config.yml'}
```

Update your database schema
```sh
bin/console doctrine:schema:update --force
```

### Integration
The Bundle overrides the `ProductVariant` class that is provided by Sylius. This will be overridden in the `resource.yml` of the Bundle. If you want to override that class in your application too, you have to merge the two configurations.

## Usage

TODO
