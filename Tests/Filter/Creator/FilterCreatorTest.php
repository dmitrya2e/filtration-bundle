<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Creator;

use Da2e\FiltrationBundle\Filter\Creator\FilterCreator;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterCreatorTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Creator
 */
class FilterCreatorTest extends TestCase
{
    public function testCreate()
    {
        $filterChainMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Chain\FilterChain', [
            'hasType',
            'getType',
        ]);

        $filterChainMock->expects($this->at(0))->method('hasType')->with('foo')->willReturn(true);
        $filterChainMock->expects($this->at(1))->method('getType')->with('foo')->willReturn(
            $filter1 = $this->getFilterMock()
        );
        $filterChainMock->expects($this->at(2))->method('hasType')->with('bar')->willReturn(true);
        $filterChainMock->expects($this->at(3))->method('getType')->with('bar')->willReturn(
            $filter2 = $this->getFilterMock()
        );

        $filterChainMock->expects($this->exactly(2))->method('getType');

        $filterCreatorMock = $this->getCustomMock(
            '\Da2e\FiltrationBundle\Filter\Creator\FilterCreator',
            ['generateUniqueName'],
            [$filterChainMock]
        );

        $filterCreatorMock->expects($this->at(0))->method('generateUniqueName')->with('foo')->willReturn('foo_unique');
        $filterCreatorMock->expects($this->at(1))->method('generateUniqueName')->with('bar')->willReturn('bar_unique');
        $filterCreatorMock->expects($this->exactly(2))->method('generateUniqueName');

        $result = $filterCreatorMock->create('foo');
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter', $result);
        $this->assertSame('foo_unique', $result->getName());

        $result = $filterCreatorMock->create('bar');
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter', $result);
        $this->assertSame('bar_unique', $result->getName());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Creator\FilterCreatorException
     */
    public function testCreate_NoFilterType()
    {
        $filterChainMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Chain\FilterChain', ['hasType']);
        $filterChainMock->expects($this->at(0))->method('hasType')->with('foo')->willReturn(false);

        $filterCreator = new FilterCreator($filterChainMock);
        $filterCreator->create('foo');
    }

    public function testGenerateUniqueName()
    {
        $filterChainMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Chain\FilterChain');
        $filterCreator = new FilterCreator($filterChainMock);

        $result = $this->invokeMethod($filterCreator, 'generateUniqueName', ['foobar']);
        $this->assertStringStartsWith('foobar_', $result);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractFilter
     */
    private function getFilterMock()
    {
        return $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter', [], ['name']);
    }
}
