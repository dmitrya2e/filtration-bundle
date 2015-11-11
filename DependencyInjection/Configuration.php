<?php

namespace Da2e\FiltrationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Da2e\FiltrationBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('da2e_filtration')
            ->children()
                ->arrayNode('handlers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('doctrine_orm')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('sphinx_api')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('custom_handlers')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('class_name')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
