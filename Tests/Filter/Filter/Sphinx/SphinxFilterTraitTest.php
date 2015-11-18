<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx;

use Da2e\FiltrationBundle\Filter\Filter\Sphinx\SphinxFilterTrait;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class SphinxFilterTraitTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class SphinxFilterTraitTest extends TestCase
{
    public function testIsExclude()
    {
        /** @var SphinxFilterTrait $mock */
        $mock = $this->getMockForTrait('\Da2e\FiltrationBundle\Filter\Filter\Sphinx\SphinxFilterTrait');
        $this->assertFalse($mock->isExclude());
    }

    public function testSetExclude()
    {
        /** @var SphinxFilterTrait $mock */
        $mock = $this->getMockForTrait('\Da2e\FiltrationBundle\Filter\Filter\Sphinx\SphinxFilterTrait');

        $mock->setExclude(true);
        $this->assertTrue($mock->isExclude());

        $mock->setExclude(false);
        $this->assertFalse($mock->isExclude());
    }

    public function testGetExcludeOptionDescription()
    {
        /** @var SphinxFilterTrait $mock */
        $mock = $this->getMockForTrait('\Da2e\FiltrationBundle\Filter\Filter\Sphinx\SphinxFilterTrait');

        $this->assertSame([
            'setter' => 'setExclude',
            'empty'  => false,
            'type'   => 'bool',
        ], $this->invokeMethod($mock, 'getExcludeOptionDescription'));
    }
}
