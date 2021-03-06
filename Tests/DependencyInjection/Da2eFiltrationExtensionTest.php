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
                'handlers' => [
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
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('foo', $result);
        $this->assertArrayHasKey('bar', $result);
        $this->assertSame('FooClass', $result['foo']);
        $this->assertSame('BarClass', $result['bar']);
    }
}
