<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Collection;

use Da2e\FiltrationBundle\Filter\Collection\BaseCollection;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class BaseCollectionTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Collection
 */
class BaseCollectionTest extends TestCase
{
    public function testInterfaces()
    {
        $baseCollection = new BaseCollection();
        $this->assertInstanceOf('\IteratorAggregate', $baseCollection);
        $this->assertInstanceOf('\Countable', $baseCollection);
    }

    public function testCount_Default()
    {
        $baseCollection = new BaseCollection();
        $this->assertSame(0, $baseCollection->count());
    }

    public function testGetIterator()
    {
        $baseCollection = new BaseCollection();
        $this->assertInstanceOf('\ArrayIterator', $baseCollection->getIterator());
    }

    public function testAdd()
    {
        $baseCollection = new BaseCollection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->atLeastOnce())->method('getName')->willReturn('foobar');

        $this->invokeMethod($baseCollection, 'add', [$filter]);
        $this->assertTrue($this->invokeMethod($baseCollection, 'has', ['foobar']));
    }

    public function testHas()
    {
        $baseCollection = new BaseCollection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->atLeastOnce())->method('getName')->willReturn('foobar');

        $this->assertFalse($this->invokeMethod($baseCollection, 'has', ['foobar']));

        $this->invokeMethod($baseCollection, 'add', [$filter]);
        $this->assertTrue($this->invokeMethod($baseCollection, 'has', ['foobar']));
        $this->assertTrue($this->invokeMethod($baseCollection, 'has', [$filter]));
        $this->assertFalse($this->invokeMethod($baseCollection, 'has', ['baz']));
    }

    public function testGet()
    {
        $baseCollection = new BaseCollection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->atLeastOnce())->method('getName')->willReturn('foobar');

        $this->assertFalse($this->invokeMethod($baseCollection, 'get', ['foobar']));

        $this->invokeMethod($baseCollection, 'add', [$filter]);
        $this->assertSame($filter, $this->invokeMethod($baseCollection, 'get', ['foobar']));
    }

    public function testRemove()
    {
        $baseCollection = new BaseCollection();
        $filter = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter->expects($this->atLeastOnce())->method('getName')->willReturn('foobar');

        $this->assertFalse($this->invokeMethod($baseCollection, 'remove', ['foobar']));
        $this->assertFalse($this->invokeMethod($baseCollection, 'remove', [$filter]));

        $this->invokeMethod($baseCollection, 'add', [$filter]);
        $this->assertTrue($this->invokeMethod($baseCollection, 'remove', ['foobar']));
        $this->assertFalse($this->invokeMethod($baseCollection, 'has', ['foobar']));

        $this->invokeMethod($baseCollection, 'add', [$filter]);
        $this->assertTrue($this->invokeMethod($baseCollection, 'remove', [$filter]));
        $this->assertFalse($this->invokeMethod($baseCollection, 'has', ['foobar']));
    }
}
