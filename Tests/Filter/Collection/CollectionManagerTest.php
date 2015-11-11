<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Collection;

use Da2e\FiltrationBundle\Filter\Collection\Collection;
use Da2e\FiltrationBundle\Filter\Collection\CollectionManager;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class CollectionManagerTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Collection
 */
class CollectionManagerTest extends TestCase
{
    public function testAdd()
    {
        $filterCreatorMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Creator\FilterCreator', ['create']);

        $filterCreatorMock->expects($this->at(0))->method('create')->with('foo')->willReturn(
            $filter1 = $this->getFilterMock()
        );

        $filterCreatorMock->expects($this->at(1))->method('create')->with('bar')->willReturn(
            $filter2 = $this->getFilterMock()
        );

        $filterCreatorMock->expects($this->exactly(2))->method('create');

        $filterOptionHandlerMock = $this->getCustomMock(
            '\Da2e\FiltrationBundle\Filter\FilterOption\FilterOptionHandler',
            ['handle']
        );

        $filterOptionHandlerMock->expects($this->once())->method('handle')->with($filter1, ['field_name' => 'foo1']);

        $filterCollection = new Collection();
        $collectionManager = new CollectionManager($filterCreatorMock, $filterOptionHandlerMock);

        $collectionManager->add('foo', 'name_1', $filterCollection, ['field_name' => 'foo1']);
        $this->assertTrue($filterCollection->containsFilter($filter1));

        $collectionManager->add('bar', 'name_2', $filterCollection);
        $this->assertTrue($filterCollection->containsFilter($filter2));
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Collection\CollectionException
     */
    public function testAdd_InvalidFilterClass()
    {
        $filterCreatorMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Creator\FilterCreator', ['create']);
        $filterCreatorMock->expects($this->at(0))->method('create')->with('foo')->willReturn($filter1 = new \stdClass());

        $filterOptionHandlerMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\FilterOption\FilterOptionHandler');

        $filterCollection = new Collection();
        $collectionManager = new CollectionManager($filterCreatorMock, $filterOptionHandlerMock);

        $collectionManager->add('foo', 'name_1', $filterCollection);
        $this->assertTrue($filterCollection->containsFilter($filter1));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractFilter
     */
    private function getFilterMock()
    {
        return $this->getCustomAbstractMock('\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter', [], ['name']);
    }
}
