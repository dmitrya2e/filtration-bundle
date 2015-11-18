<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Manager;

use Da2e\FiltrationBundle\Filter\Collection\Collection;
use Da2e\FiltrationBundle\Filter\Collection\CollectionManager;
use Da2e\FiltrationBundle\Filter\Collection\Creator\CollectionCreator;
use Da2e\FiltrationBundle\Filter\Executor\FilterExecutor;
use Da2e\FiltrationBundle\Form\Creator\FormCreator;
use Da2e\FiltrationBundle\Manager\FilterSuperManager;
use Da2e\FiltrationBundle\Tests\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FilterSuperManagerTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterSuperManagerTest extends TestCase
{
    public function testConstruct()
    {
        $filterSuperManager = new FilterSuperManager(
            $this->getCollectionCreatorMock(),
            $this->getCollectionManagerMock(),
            $this->getFormCreatorMock(),
            $this->getFilterExecutorMock()
        );
    }

    public function testCreateFilterCollection()
    {
        $filterSuperManager = new FilterSuperManager(
            $collectionCreatorMock = $this->getCollectionCreatorMock(['create']),
            $this->getCollectionManagerMock(),
            $this->getFormCreatorMock(),
            $this->getFilterExecutorMock()
        );

        $collectionCreatorMock->expects($this->once())->method('create')->willReturn('foobar');
        $this->assertSame('foobar', $filterSuperManager->createFilterCollection());
    }

    public function testAddFilter()
    {
        $filterSuperManager = new FilterSuperManager(
            $this->getCollectionCreatorMock(),
            $collectionManagerMock = $this->getCollectionManagerMock(['add']),
            $this->getFormCreatorMock(),
            $this->getFilterExecutorMock()
        );

        $collectionMock = $this->getCollectionMock();

        $collectionManagerMock->expects($this->once())->method('add')
            ->with('type_alias', 'name', $collectionMock, ['options'])
            ->willReturn('foobar');

        $this->assertSame('foobar', $filterSuperManager->addFilter($collectionMock, 'type_alias', 'name', ['options']));
    }

    public function testCreateForm()
    {
        $filterSuperManager = new FilterSuperManager(
            $this->getCollectionCreatorMock(),
            $this->getCollectionManagerMock(),
            $formCreatorMock = $this->getFormCreatorMock(['create']),
            $this->getFilterExecutorMock()
        );

        $collectionMock = $this->getCollectionMock();

        $formCreatorMock->expects($this->once())->method('create')
            ->with($collectionMock, ['root options'], ['filter options'])
            ->willReturn('foobar');

        $result = $filterSuperManager->createForm($collectionMock, ['root options'], ['filter options']);
        $this->assertSame('foobar', $result);
    }

    public function testCreateForm_WithRequest()
    {
        $filterSuperManager = new FilterSuperManager(
            $this->getCollectionCreatorMock(),
            $this->getCollectionManagerMock(),
            $formCreatorMock = $this->getFormCreatorMock(['create', 'handleRequest']),
            $this->getFilterExecutorMock()
        );

        $requestMock = $this->getRequestMock();
        $collectionMock = $this->getCollectionMock();

        $formInterfaceMock = $this->getFormInterfaceMock(['handleRequest']);
        $formInterfaceMock->expects($this->once())->method('handleRequest')->with($requestMock);

        $formCreatorMock->expects($this->once())->method('create')
            ->with($collectionMock, ['root options'], ['filter options'])
            ->willReturn($formInterfaceMock);

        $result = $filterSuperManager->createForm($collectionMock, ['root options'], ['filter options'], $requestMock);
        $this->assertSame($formInterfaceMock, $result);
    }

    public function testCreateNamedForm()
    {
        $filterSuperManager = new FilterSuperManager(
            $this->getCollectionCreatorMock(),
            $this->getCollectionManagerMock(),
            $formCreatorMock = $this->getFormCreatorMock(['createNamed']),
            $this->getFilterExecutorMock()
        );

        $collectionMock = $this->getCollectionMock();

        $formCreatorMock->expects($this->once())->method('createNamed')
            ->with('name', $collectionMock, ['root options'], ['filter options'])
            ->willReturn('foobar');

        $result = $filterSuperManager->createNamedForm('name', $collectionMock, ['root options'], ['filter options']);
        $this->assertSame('foobar', $result);
    }

    public function testCreateNamedForm_WithRequest()
    {
        $filterSuperManager = new FilterSuperManager(
            $this->getCollectionCreatorMock(),
            $this->getCollectionManagerMock(),
            $formCreatorMock = $this->getFormCreatorMock(['createNamed', 'handleRequest']),
            $this->getFilterExecutorMock()
        );

        $requestMock = $this->getRequestMock();
        $collectionMock = $this->getCollectionMock();

        $formInterfaceMock = $this->getFormInterfaceMock(['handleRequest']);
        $formInterfaceMock->expects($this->once())->method('handleRequest')->with($requestMock);

        $formCreatorMock->expects($this->once())->method('createNamed')
            ->with('name', $collectionMock, ['root options'], ['filter options'])
            ->willReturn($formInterfaceMock);

        $result = $filterSuperManager->createNamedForm('name', $collectionMock, ['root options'], ['filter options'], $requestMock);
        $this->assertSame($formInterfaceMock, $result);
    }

    public function testExecuteFilters()
    {
        $filterSuperManager = new FilterSuperManager(
            $this->getCollectionCreatorMock(),
            $this->getCollectionManagerMock(['add']),
            $this->getFormCreatorMock(),
            $filterExecutorMock = $this->getFilterExecutorMock(['execute'])
        );

        $collectionMock = $this->getCollectionMock();
        $handlers = ['foo', 'bar'];

        $filterExecutorMock->expects($this->once())->method('execute')
            ->with($collectionMock, $handlers)
            ->willReturn('foobar');

        $this->assertSame('foobar', $filterSuperManager->executeFilters($collectionMock, $handlers));
    }

    /**
     * @param array|null|false $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectionCreator
     */
    private function getCollectionCreatorMock($methods = null)
    {
        return $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Collection\Creator\CollectionCreator', $methods);
    }

    /**
     * @param array|null|false $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectionManager
     */
    private function getCollectionManagerMock($methods = null)
    {
        return $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Collection\CollectionManager', $methods);
    }

    /**
     * @param array|null|false $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Collection
     */
    private function getCollectionMock($methods = null)
    {
        return $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Collection\Collection', $methods);
    }

    /**
     * @param array|null|false $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Request
     */
    private function getRequestMock($methods = null)
    {
        return $this->getCustomMock('\Symfony\Component\HttpFoundation\Request', $methods);
    }

    /**
     * @param array|null|false $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|FormCreator
     */
    private function getFormCreatorMock($methods = null)
    {
        return $this->getCustomMock('\Da2e\FiltrationBundle\Form\Creator\FormCreator', $methods);
    }

    /**
     * @param array|null|false $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|FilterExecutor
     */
    private function getFilterExecutorMock($methods = null)
    {
        return $this->getCustomMock('\Da2e\FiltrationBundle\Filter\Executor\FilterExecutor', $methods);
    }

    /**
     * @param array|null|false $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|FormInterface
     */
    private function getFormInterfaceMock($methods = [])
    {
        return $this->getCustomAbstractMock('\Symfony\Component\Form\FormInterface', $methods);
    }
}
