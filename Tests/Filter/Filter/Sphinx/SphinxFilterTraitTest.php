<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx;

use Da2e\FiltrationBundle\Filter\Filter\Sphinx\SphinxFilterTrait;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class SphinxFilterTraitTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx
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
