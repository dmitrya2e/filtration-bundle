<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This class finds tagged services and adds them to the filter chain.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FiltrationCompilePass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
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
