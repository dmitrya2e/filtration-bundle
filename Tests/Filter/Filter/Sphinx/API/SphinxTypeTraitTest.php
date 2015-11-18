<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API;

use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException;
use Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\SphinxTypeTrait;
use Da2e\FiltrationBundle\Model\FilterHandlerModel;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class SphinxTypeTraitTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class SphinxTypeTraitTest extends TestCase
{
    public function testGetType()
    {
        /** @var SphinxTypeTrait $mock */
        $mock = $this->getMockForTrait('\Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\SphinxTypeTrait');
        $this->assertSame(FilterHandlerModel::HANDLER_SPHINX_API, $mock->getType());
    }

    public function testCheckSphinxHandlerInstance()
    {
        /** @var SphinxTypeTrait $mock */
        $mock = $this->getMockForTrait('\Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\SphinxTypeTrait');
        $this->invokeMethod($mock, 'checkSphinxHandlerInstance', [
            $this->getMock('\SphinxClient', [], [], '', false)
        ]);
    }

    public function testCheckSphinxHandlerInstance_InvalidHandler()
    {
        $args = [
            1,
            1.0,
            null,
            0,
            new \stdClass(),
            [],
            function () {
            },
            true,
            false,
            '',
        ];

        $exceptionCount = 0;
        $mock = $this->getMockForTrait('\Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\SphinxTypeTrait');

        foreach ($args as $arg) {
            try {
                $this->invokeMethod($mock, 'checkSphinxHandlerInstance', [$arg]);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }
}
