<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\DependencyInjection;

use Da2e\FiltrationBundle\DependencyInjection\Da2eFiltrationExtension;
use Da2e\FiltrationBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class Da2eFiltrationExtensionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FiltrationExtensionTest extends TestCase
{
    public function testLoad()
    {
        $containerBuilder = new ContainerBuilder();
        $extension = new Da2eFiltrationExtension();

        $configs = [
            'da2e_filtration' => [
                'handlers'        => [
                    'doctrine_orm' => false,
                    'sphinx_api'   => true,
                ],
                'custom_handlers' => [
                    [
                        'name'       => 'foo',
                        'class_name' => 'FooClass',
                    ],
                    [
                        'name'       => 'bar',
                        'class_name' => 'BarClass',
                    ],
                ],
            ],
        ];

        $extension->load($configs, $containerBuilder);

        $result = $containerBuilder->getParameter('da2e.filtration.config.handler_types');
        $this->assertTrue(is_array($result));
        $this->assertCount(3, $result);
        $this->assertArrayHasKey('sphinx_api', $result);
        $this->assertArrayHasKey('foo', $result);
        $this->assertArrayHasKey('bar', $result);
        $this->assertSame('\SphinxClient', $result['sphinx_api']);
        $this->assertSame('FooClass', $result['foo']);
        $this->assertSame('BarClass', $result['bar']);

        $disabledServices = [
            'da2e.filtration.filter.filter.doctrine.orm.text_filter',
            'da2e.filtration.filter.filter.doctrine.orm.number_filter',
            'da2e.filtration.filter.filter.doctrine.orm.date_filter',
            'da2e.filtration.filter.filter.doctrine.orm.choice_filter',
            'da2e.filtration.filter.filter.doctrine.orm.entity_filter',
        ];

        foreach ($disabledServices as $service) {
            $this->assertFalse($containerBuilder->has($service));
        }

        $enabledServices = [
            'da2e.filtration.filter.filter.sphinx.api.text_filter',
            'da2e.filtration.filter.filter.sphinx.api.number_filter',
            'da2e.filtration.filter.filter.sphinx.api.date_filter',
            'da2e.filtration.filter.filter.sphinx.api.choice_filter',
            'da2e.filtration.filter.filter.sphinx.api.entity_filter',
        ];

        foreach ($enabledServices as $service) {
            $this->assertTrue($containerBuilder->has($service));
        }
    }
}
