<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API;

use Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\ChoiceFilter;
use Da2e\FiltrationBundle\Tests\Filter\Filter\AbstractFilterTestCase;

/**
 * Class ChoiceFilterTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API
 */
class ChoiceFilterTest extends AbstractFilterTestCase
{
    public function testGetValidOptions()
    {
        $this->assertTrue(is_array(ChoiceFilter::getValidOptions()));
    }

    public function testApplyFilter()
    {
        $handler = $this->getMock('\SphinxClient', ['SetFilter'], [], '', false);
        $handler->expects($this->never())->method($this->anything());

        /** @var ChoiceFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\ChoiceFilter', [
            'checkSphinxHandlerInstance',
        ]);

        $mock->expects($this->once())->method('checkSphinxHandlerInstance')->with($handler);

        $mock->setValue([]);
        $mock->applyFilter($handler);
    }

    public function testApplyFilter_HasAppliedValue()
    {
        $handler = $this->getMock('\SphinxClient', ['SetFilter'], [], '', false);
        $handler->expects($this->once())->method('SetFilter')->with('foo', [1, 2, 3], false);
        $handler->expects($this->never())->method($this->logicalNot($this->matches('SetFilter')));

        /** @var ChoiceFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\ChoiceFilter', [
            'checkSphinxHandlerInstance',
        ]);

        $mock->expects($this->once())->method('checkSphinxHandlerInstance')->with($handler);

        $mock->setValue([1, 2, 3]);
        $mock->setFieldName('foo');
        $mock->applyFilter($handler);
    }

    public function testApplyFilter_HasAppliedValue_Exclude()
    {
        $handler = $this->getMock('\SphinxClient', ['SetFilter'], [], '', false);
        $handler->expects($this->once())->method('SetFilter')->with('foo', [1, 2, 3], true);
        $handler->expects($this->never())->method($this->logicalNot($this->matches('SetFilter')));

        /** @var ChoiceFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\ChoiceFilter', [
            'checkSphinxHandlerInstance',
        ]);

        $mock->expects($this->once())->method('checkSphinxHandlerInstance')->with($handler);

        $mock->setValue([1, 2, 3]);
        $mock->setFieldName('foo');
        $mock->setExclude(true);
        $mock->applyFilter($handler);
    }
}
