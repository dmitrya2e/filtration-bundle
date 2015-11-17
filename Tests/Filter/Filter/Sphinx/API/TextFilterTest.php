<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API;

use Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\TextFilter;
use Da2e\FiltrationBundle\Tests\Filter\Filter\AbstractFilterTestCase;

/**
 * Class TextFilterTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API
 */
class TextFilterTest extends AbstractFilterTestCase
{
    public function testGetValidOptions()
    {
        $this->assertTrue(is_array(TextFilter::getValidOptions()));
        $this->assertSame($this->getAbstractFilterValidOptions(), TextFilter::getValidOptions());
    }

    public function testApplyFilter()
    {
        $handler = $this->getMock('\SphinxClient', ['SetFilter'], [], '', false);
        $handler->expects($this->never())->method($this->anything());

        /** @var TextFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\TextFilter', [
            'checkSphinxHandlerInstance',
        ]);

        $mock->expects($this->never())->method('checkSphinxHandlerInstance')->with($handler);

        $mock->setValue([]);
        $mock->applyFilter($handler);
    }

    public function testApplyFilter_NotSphinxClientHandler()
    {
        $handler = $this->getMock('\SphinxClient', ['SetFilter'], [], '', false);
        $handler->expects($this->never())->method($this->anything());

        /** @var TextFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\TextFilter', [
            'checkSphinxHandlerInstance',
        ]);

        $mock->expects($this->never())->method('checkSphinxHandlerInstance')->with($handler);

        $mock->setValue([]);
        $mock->applyFilter('foobar');
    }

    public function testApplyFilter_HasAppliedValue()
    {
        $handler = $this->getMock('\SphinxClient', ['SetFilter'], [], '', false);
        $handler->expects($this->never())->method($this->anything());

        /** @var TextFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Sphinx\API\TextFilter', [
            'checkSphinxHandlerInstance',
        ]);

        $mock->expects($this->never())->method('checkSphinxHandlerInstance')->with($handler);

        $mock->setValue('foobar');
        $mock->setFieldName('foo');
        $mock->applyFilter($handler);
    }
}
