<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API;

use Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\EntityFilter;
use Da2e\FiltrationBundle\Tests\Filter\Filter\AbstractFilterTestCase;

/**
 * Class EntityFilterTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API
 */
class EntityFilterTest extends AbstractFilterTestCase
{
    public function testGetValidOptions()
    {
        $this->assertTrue(is_array(EntityFilter::getValidOptions()));
    }

    public function testApplyFilter()
    {
        $handler = $this->getMock('\SphinxClient', ['SetFilter'], [], '', false);
        $handler->expects($this->never())->method($this->anything());

        /** @var EntityFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\EntityFilter', [
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

        /** @var EntityFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\EntityFilter', [
            'checkSphinxHandlerInstance',
        ]);

        $mock->expects($this->once())->method('checkSphinxHandlerInstance')->with($handler);

        $e1 = $this->getMock('\stdClass', ['getId']);
        $e1->expects($this->any())->method('getId')->willReturn(1);

        $e2 = $this->getMock('\stdClass', ['getId']);
        $e2->expects($this->any())->method('getId')->willReturn(2);

        $e3 = $this->getMock('\stdClass', ['getId']);
        $e3->expects($this->any())->method('getId')->willReturn(3);

        $mock->setValue([$e1, $e2, $e3]);
        $mock->setFieldName('foo');
        $mock->applyFilter($handler);
    }

    public function testApplyFilter_HasAppliedValue_Exclude()
    {
        $handler = $this->getMock('\SphinxClient', ['SetFilter'], [], '', false);
        $handler->expects($this->once())->method('SetFilter')->with('foo', [1, 2, 3], true);
        $handler->expects($this->never())->method($this->logicalNot($this->matches('SetFilter')));

        /** @var EntityFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\EntityFilter', [
            'checkSphinxHandlerInstance',
        ]);

        $mock->expects($this->once())->method('checkSphinxHandlerInstance')->with($handler);

        $e1 = $this->getMock('\stdClass', ['getId']);
        $e1->expects($this->any())->method('getId')->willReturn(1);

        $e2 = $this->getMock('\stdClass', ['getId']);
        $e2->expects($this->any())->method('getId')->willReturn(2);

        $e3 = $this->getMock('\stdClass', ['getId']);
        $e3->expects($this->any())->method('getId')->willReturn(3);

        $mock->setValue([$e1, $e2, $e3]);
        $mock->setFieldName('foo');
        $mock->setExclude(true);
        $mock->applyFilter($handler);
    }
}
