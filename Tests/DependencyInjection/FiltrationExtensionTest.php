<?php

namespace Da2e\FiltrationBundle\Tests\DependencyInjection;

use Da2e\FiltrationBundle\DependencyInjection\FiltrationExtension;
use Da2e\FiltrationBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FiltrationExtensionTest
 * @package Da2e\FiltrationBundle\Tests\DependencyInjection
 */
class FiltrationExtensionTest extends TestCase
{
    public function testLoad()
    {
        $containerBuilder = new ContainerBuilder();
        $extension = new FiltrationExtension();

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
    }
}
