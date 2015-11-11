<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Executor;

use Da2e\FiltrationBundle\Filter\Collection\Collection;
use Da2e\FiltrationBundle\Filter\Executor\FilterExecutor;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterExecutorTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Executor
 */
class FilterExecutorTest extends TestCase
{
    public function testHandle()
    {
        $filter1 = $this->getFilterMock(['getType'])->setName('name1');
        $filter1->expects($this->atLeastOnce())->method('getType')->willReturn('doctrine_orm');
        $filter2 = $this->getFilterMock(['getType'])->setName('name2');
        $filter2->expects($this->atLeastOnce())->method('getType')->willReturn('doctrine_orm');

        $collection = new Collection();
        $collection->addFilter($filter1);
        $collection->addFilter($filter2);

        $handler = new \stdClass();
        $filterExecutorMock = $this->getCustomMock(
            '\Da2e\FiltrationBundle\Filter\Executor\FilterExecutor',
            ['transformValues', 'applyFilter'],
            [['doctrine_orm' => '\stdClass']]
        );

        $filterExecutorMock->expects($this->at(0))->method('transformValues')->with($filter1);
        $filterExecutorMock->expects($this->at(1))->method('applyFilter')->with($filter1, $handler);
        $filterExecutorMock->expects($this->at(2))->method('transformValues')->with($filter2);
        $filterExecutorMock->expects($this->at(3))->method('applyFilter')->with($filter2, $handler);
        $filterExecutorMock->expects($this->exactly(2))->method('transformValues');
        $filterExecutorMock->expects($this->exactly(2))->method('applyFilter');

        $filterExecutorMock->execute($collection, [new \stdClass()]);
    }

    public function testHandle_ExplicitHandlerType()
    {
        $filter1 = $this->getFilterMock(['getType'])->setName('name1');
        $filter1->expects($this->atLeastOnce())->method('getType')->willReturn('doctrine_orm');
        $filter2 = $this->getFilterMock(['getType'])->setName('name2');
        $filter2->expects($this->atLeastOnce())->method('getType')->willReturn('doctrine_orm');

        $collection = new Collection();
        $collection->addFilter($filter1);
        $collection->addFilter($filter2);

        $handler = new \stdClass();
        $filterExecutorMock = $this->getCustomMock(
            '\Da2e\FiltrationBundle\Filter\Executor\FilterExecutor',
            ['transformValues', 'applyFilter'],
            [['doctrine_orm' => '\stdClass']]
        );

        $filterExecutorMock->expects($this->at(0))->method('transformValues')->with($filter1);
        $filterExecutorMock->expects($this->at(1))->method('applyFilter')->with($filter1, $handler);
        $filterExecutorMock->expects($this->at(2))->method('transformValues')->with($filter2);
        $filterExecutorMock->expects($this->at(3))->method('applyFilter')->with($filter2, $handler);
        $filterExecutorMock->expects($this->exactly(2))->method('transformValues');
        $filterExecutorMock->expects($this->exactly(2))->method('applyFilter');

        $filterExecutorMock->execute($collection, ['doctrine_orm' => new \stdClass()]);
    }

    public function testHandle_NoFilters()
    {
        $collection = new Collection();
        $handler = new \stdClass();

        $filterExecutorMock = $this->getCustomMock(
            '\Da2e\FiltrationBundle\Filter\Executor\FilterExecutor',
            ['transformValues', 'applyFilter'],
            [['doctrine_orm' => '\stdClass']]
        );

        $filterExecutorMock->expects($this->never())->method('transformValues');
        $filterExecutorMock->expects($this->never())->method('applyFilter');
        $filterExecutorMock->execute($collection, [new \stdClass()]);
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Executor\FilterExecutorException
     */
    public function testHandle_NoRegisteredHandlerType()
    {
        $filter1 = $this->getFilterMock();
        $collection = new Collection();
        $collection->addFilter($filter1);

        $filterExecutorMock = $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Executor\FilterExecutor');

        $filterExecutorMock->expects($this->never())->method('transformValues');
        $filterExecutorMock->expects($this->never())->method('applyFilter');
        $filterExecutorMock->execute($collection, [new \stdClass()]);
    }

    public function testGuessHandlerType()
    {
        $filterExecutor = new FilterExecutor(['doctrine_orm' => '\stdClass']);
        $result = $this->invokeMethod($filterExecutor, 'guessHandlerType', [new \stdClass()]);
        $this->assertSame('doctrine_orm', $result);
    }

    public function testGuessHandlerType_CouldNotGuess()
    {
        $filterExecutor = new FilterExecutor([]);
        $result = $this->invokeMethod($filterExecutor, 'guessHandlerType', [new \stdClass()]);
        $this->assertFalse($result);
    }

    public function testGuessHandlerType_HandlerIsNotObject()
    {
        $filterExecutor = new FilterExecutor([]);
        $result = $this->invokeMethod($filterExecutor, 'guessHandlerType', ['foobar']);
        $this->assertFalse($result);
    }

    public function testRegisterHandler()
    {
        $filter1 = $this->getFilterMock(['getType']);
        $filter1->expects($this->atLeastOnce())->method('getType')->willReturn('doctrine_orm');

        $collection = new Collection();
        $collection->addFilter($filter1);

        $filterExecutor = new FilterExecutor(['doctrine_orm' => '\stdClass']);
        $filterExecutor->registerHandler(new \stdClass(), 'doctrine_orm');

        // No exception should be thrown
        $filterExecutor->execute($collection);
    }

    public function testRegisterHandler_NoExplicitType()
    {
        $filter1 = $this->getFilterMock(['getType']);
        $filter1->expects($this->atLeastOnce())->method('getType')->willReturn('doctrine_orm');

        $collection = new Collection();
        $collection->addFilter($filter1);

        $filterExecutor = new FilterExecutor(['doctrine_orm' => '\stdClass']);
        $filterExecutor->registerHandler(new \stdClass());

        // No exception should be thrown
        $filterExecutor->execute($collection);
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Executor\FilterExecutorException
     */
    public function testRegisterHandler_HandlerIsNotObject()
    {
        $filterExecutor = new FilterExecutor(['doctrine_orm' => '\stdClass']);
        $filterExecutor->registerHandler('foobar', 'doctrine_orm');
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Executor\FilterExecutorException
     */
    public function testRegisterHandler_CouldNotGuessType()
    {
        $filterExecutor = new FilterExecutor(['doctrine_orm' => 'foobar']);
        $filterExecutor->registerHandler(new \stdClass());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Executor\FilterExecutorException
     */
    public function testRegisterHandler_HandlerIsNotAvailable()
    {
        $filterExecutor = new FilterExecutor([]);
        $filterExecutor->registerHandler(new \stdClass(), 'doctrine_orm');
    }

    public function testRegisterHandlers()
    {
        $handler1 = new \stdClass();
        $handler2 = new \stdClass();
        $handler3 = $this->getCustomMock('\Foobar');

        $filterExecutorMock = $this->getCustomMock(
            '\Da2e\FiltrationBundle\Filter\Executor\FilterExecutor',
            ['registerHandler'],
            [['doctrine_orm' => '\stdClass']]
        );

        $filterExecutorMock->expects($this->at(0))->method('registerHandler')->with($handler1, 'doctrine_orm');
        $filterExecutorMock->expects($this->at(1))->method('registerHandler')->with($handler2, 'doctrine_orm');
        $filterExecutorMock->expects($this->at(2))->method('registerHandler')->with($handler3, false);
        $filterExecutorMock->expects($this->exactly(3))->method('registerHandler');

        $filterExecutorMock->registerHandlers([
            $handler1,
            'doctrine_orm' => $handler2,
            $handler3
        ]);
    }

    public function testApplyFilter()
    {
        $handler = new \stdClass();

        $filterExecutor = new FilterExecutor();
        $filterMock = $this->getFilterMock(['applyFilter']);
        $filterMock->expects($this->once())->method('applyFilter')->with($handler);

        $this->invokeMethod($filterExecutor, 'applyFilter', [$filterMock, $handler]);
    }

    public function testApplyFilter_CustomFunction()
    {
        $handler = $this->getCustomMock('\stdClass', ['foo']);
        $handler->expects($this->once())->method('foo');

        $filterExecutor = new FilterExecutor();

        $filterMock = $this->getFilterMock(['applyFilter', 'getApplyFilterFunction', 'bar']);
        $filterMock->expects($this->never())->method('applyFilter');
        $filterMock->expects($this->once())->method('bar');
        $filterMock->expects($this->atLeastOnce())->method('getApplyFilterFunction')->willReturn(
            function ($filter, $handler) {
                $filter->bar();
                $handler->foo();
            }
        );

        $this->invokeMethod($filterExecutor, 'applyFilter', [$filterMock, $handler]);
    }

    public function testApplyFilter_CustomFunction_NotCallable()
    {
        $handler = new \stdClass();
        $filterExecutor = new FilterExecutor();

        $filterMock = $this->getFilterMock(['applyFilter', 'getApplyFilterFunction']);
        $filterMock->expects($this->once())->method('applyFilter')->with($handler);
        $filterMock->expects($this->atLeastOnce())->method('getApplyFilterFunction')->willReturn('foobar');

        $this->invokeMethod($filterExecutor, 'applyFilter', [$filterMock, $handler]);
    }

    public function testTransformValues()
    {
        $filterExecutor = new FilterExecutor();
        $filterMock = $this->getCustomAbstractMock(
            '\Da2e\FiltrationBundle\Filter\Filter\FilterInterface',
            ['getTransformValuesFunction']
        );
        $filterMock->expects($this->never())->method('getTransformValuesFunction');

        $this->invokeMethod($filterExecutor, 'transformValues', [$filterMock]);
    }

    public function testTransformValues_CustomFunction()
    {
        $filterExecutor = new FilterExecutor();

        $filterMock = $this->getFilterMock(['getTransformValuesFunction', 'bar']);
        $filterMock->expects($this->once())->method('bar');
        $filterMock->expects($this->atLeastOnce())->method('getTransformValuesFunction')->willReturn(
            function ($filter) {
                $filter->bar();
            }
        );

        $this->invokeMethod($filterExecutor, 'transformValues', [$filterMock]);
    }

    public function testTransformValues_CustomFunction_NotCallable()
    {
        $filterExecutor = new FilterExecutor();

        $filterMock = $this->getFilterMock(['getTransformValuesFunction']);
        $filterMock->expects($this->atLeastOnce())->method('getTransformValuesFunction')->willReturn('foobar');

        $this->invokeMethod($filterExecutor, 'transformValues', [$filterMock]);
    }

    /**
     * @param array|null|false $methods
     * @param array|null|false $constructorArgs
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractFilter
     */
    private function getFilterMock($methods = [], $constructorArgs = ['name'])
    {
        return $this->getCustomAbstractMock(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter',
            $methods,
            $constructorArgs
        );
    }
}
