<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Collection;

use Da2e\FiltrationBundle\Filter\Collection\Collection;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class CollectionTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Collection
 */
class CollectionTest extends TestCase
{
    public function testInterfaces()
    {
        $collection = new Collection();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Filter\Collection\CollectionInterface', $collection);
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Filter\Collection\BaseCollection', $collection);
    }

    public function testAddFilter()
    {
        $collection = new Collection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->any())->method('getName')->willReturn('foobar');

        $collection->addFilter($filter);
        $this->assertSame($filter, $collection->getFilterByName('foobar'));
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Collection\CollectionException
     */
    public function testAddFilter_DuplicateFilterMustThrowException()
    {
        $collection = new Collection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->any())->method('getName')->willReturn('foobar');

        $collection->addFilter($filter);
        $collection->addFilter($filter);
    }

    public function testGetFilterByName()
    {
        $collection = new Collection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->any())->method('getName')->willReturn('foobar');

        $this->assertFalse($collection->getFilterByName('foobar'));

        $collection->addFilter($filter);
        $this->assertSame($filter, $collection->getFilterByName('foobar'));
    }

    public function testRemoveFilterByName()
    {
        $collection = new Collection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->any())->method('getName')->willReturn('foobar');

        $this->assertFalse($collection->removeFilterByName('foobar'));

        $collection->addFilter($filter);
        $this->assertTrue($collection->removeFilterByName('foobar'));
    }

    public function testContainsFilter()
    {
        $collection = new Collection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->any())->method('getName')->willReturn('foobar');

        $this->assertFalse($collection->containsFilter('foobar'));

        $collection->addFilter($filter);
        $this->assertTrue($collection->containsFilter('foobar'));
        $this->assertTrue($collection->containsFilter($filter));
    }

    public function testHasFilters()
    {
        $collection = new Collection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->any())->method('getName')->willReturn('foobar');

        $this->assertFalse($collection->hasFilters());

        $collection->addFilter($filter);
        $this->assertTrue($collection->hasFilters());

        $collection->removeFilterByName('foobar');
        $this->assertFalse($collection->hasFilters());
    }
}
