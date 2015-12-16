<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This class handles applied configuration of the bundle and sets appropriate container parameters.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class Da2eFiltrationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Loading additional configs.
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // Default handler types.
        $handlerTypes = [];

        // Merge default and custom handlers.
        if (count($config['handlers']) > 0) {
            $customHandlerTypes = [];

            foreach ($config['handlers'] as $handler) {
                $customHandlerTypes[$handler['name']] = $handler['class_name'];
            }

            $handlerTypes = array_merge($handlerTypes, $customHandlerTypes);
        }

        // ... and register it as a parameter.
        $container->setParameter('da2e.filtration.config.handler_types', $handlerTypes);
    }
}
