<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Filter\Collection;

use Da2e\FiltrationBundle\Filter\Collection\BaseCollection;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class BaseCollectionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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

    public function testCount()
    {
        $baseCollection = new BaseCollection();

        $filter1 = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter1->expects($this->atLeastOnce())->method('getName')->willReturn('foo');

        $filter2 = $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
        $filter2->expects($this->atLeastOnce())->method('getName')->willReturn('bar');

        $this->invokeMethod($baseCollection, 'add', [$filter1]);
        $this->assertSame(1, $baseCollection->count());
        $this->invokeMethod($baseCollection, 'add', [$filter2]);
        $this->assertSame(2, $baseCollection->count());
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
