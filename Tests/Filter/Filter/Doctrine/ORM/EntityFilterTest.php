<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Doctrine\ORM;

use Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM\EntityFilter;

/**
 * Class EntityFilterTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter\Doctrine\ORM
 */
class EntityFilterTest extends DoctrineORMFilterTestCase
{
    public function testApplyFilter()
    {
        $handler = $this->getDoctrineORMQueryBuilderMock();
        $handler->expects($this->never())->method($this->anything());

        /** @var EntityFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM\EntityFilter', [
            'checkDoctrineORMHandlerInstance',
        ]);

        $mock->expects($this->once())->method('checkDoctrineORMHandlerInstance')->with($handler);

        $mock->setValue([]);
        $mock->applyFilter($handler);
    }

    public function testApplyFilter_HasAppliedValue()
    {
        $handler = $this->getDoctrineORMQueryBuilderMock();
        $handler->expects($this->once())->method('andWhere')->with('foo IN (:bar)')->willReturn($handler);
        $handler->expects($this->once())->method('setParameter')->with('bar', [1, 2, 3]);
        $handler->expects($this->never())->method($this->logicalNot(
            $this->logicalOr(
                $this->matches('andWhere'),
                $this->matches('setParameter')
            )
        ));

        /** @var EntityFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM\EntityFilter', [
            'checkDoctrineORMHandlerInstance',
        ]);

        $mock->expects($this->once())->method('checkDoctrineORMHandlerInstance')->with($handler);

        $e1 = $this->getMock('\stdClass', ['getId']);
        $e1->expects($this->any())->method('getId')->willReturn(1);

        $e2 = $this->getMock('\stdClass', ['getId']);
        $e2->expects($this->any())->method('getId')->willReturn(2);

        $e3 = $this->getMock('\stdClass', ['getId']);
        $e3->expects($this->any())->method('getId')->willReturn(3);

        $mock->setValue([$e1, $e2, $e3]);
        $mock->setFieldName('foo');
        $mock->setName('bar');
        $mock->applyFilter($handler);
    }
}
