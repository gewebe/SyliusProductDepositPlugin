<?php

declare(strict_types=1);

namespace Gewebe\SyliusProductDepositPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('gewebe_product_deposit');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
