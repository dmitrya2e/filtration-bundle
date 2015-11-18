<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Filter\Chain;

use Da2e\FiltrationBundle\Filter\Chain\FilterChain;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterChainTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterChainTest extends TestCase
{
    public function testAddType()
    {
        $filter = $this->getFilterMock();
        $filterChain = new FilterChain();

        $this->assertFalse($filterChain->getType('foobar'));

        $filterChain->addType($filter, 'foobar');
        $result = $filterChain->getType('foobar');
        $this->assertSame($filter, $result);
    }

    public function testGetType()
    {
        $filterChain = new FilterChain();
        $this->assertFalse($filterChain->getType('foobar'));

        $filter = $this->getFilterMock();
        $filterChain->addType($filter, 'foobar');
        $result = $filterChain->getType('foobar');
        $this->assertSame($filter, $result);
    }

    public function testHasType()
    {
        $filterChain = new FilterChain();
        $this->assertFalse($filterChain->hasType('foobar'));

        $filter = $this->getFilterMock();
        $filterChain->addType($filter, 'foobar');
        $this->assertTrue($filterChain->hasType('foobar'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractFilter
     */
    private function getFilterMock()
    {
        return $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter', [], ['name']);
    }
}
