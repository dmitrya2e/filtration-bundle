<?php

namespace Da2e\FiltrationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FiltrationCompilePass
 * @package Da2e\FiltrationBundle\DependencyInjection\Compiler
 */
class FiltrationCompilePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('da2e.filtration.filter.chain.filter_chain')) {
            return;
        }

        $definition = $container->getDefinition('da2e.filtration.filter.chain.filter_chain');
        $taggedServices = $container->findTaggedServiceIds('da2e.filtration.manager.filter');

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall('addType', [new Reference($id), $attributes["alias"]]);
            }
        }
    }
}
