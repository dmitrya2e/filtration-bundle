<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Filter\Creator;

use Da2e\FiltrationBundle\Filter\Creator\FilterCreator;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Filter\FilterOption\FilterOptionHandler;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterCreatorTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
            [$filterChainMock, new FilterOptionHandler()]
        );

        $filterCreatorMock->expects($this->once())->method('generateUniqueName')->with('bar')->willReturn('bar_unique');

        $result = $filterCreatorMock->create('foo', 'foo_name', ['field_name' => 'foo_field']);
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter', $result);
        $this->assertSame('foo_name', $result->getName());
        $this->assertSame('foo_field', $result->getFieldName());

        $result = $filterCreatorMock->create('bar');
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter', $result);
        $this->assertSame('bar_unique', $result->getName());
        $this->assertEmpty($result->getFieldName());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Creator\FilterCreatorException
     */
    public function testCreate_NoFilterType()
    {
        $filterChainMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Chain\FilterChain', ['hasType']);
        $filterChainMock->expects($this->at(0))->method('hasType')->with('foo')->willReturn(false);

        $filterCreator = new FilterCreator($filterChainMock, new FilterOptionHandler());
        $filterCreator->create('foo');
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Creator\FilterCreatorException
     */
    public function testCreate_InvalidFilterClass()
    {
        $filterChainMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Chain\FilterChain', ['hasType', 'getType']);
        $filterChainMock->expects($this->atLeastOnce())->method('hasType')->with('foo')->willReturn(true);
        $filterChainMock->expects($this->atLeastOnce())->method('getType')->with('foo')->willReturn(new \stdClass());

        $filterCreator = new FilterCreator($filterChainMock, new FilterOptionHandler());
        $filterCreator->create('foo');
    }

    public function testGenerateUniqueName()
    {
        $filterChainMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Chain\FilterChain');
        $filterCreator = new FilterCreator($filterChainMock, new FilterOptionHandler());

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
