<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Doctrine\ORM;

use Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM\ChoiceFilter;

/**
 * Class ChoiceFilterTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter\Doctrine\ORM
 */
class ChoiceFilterTest extends DoctrineORMFilterTestCase
{
    public function testApplyFilter()
    {
        $handler = $this->getDoctrineORMQueryBuilderMock();

        /** @var ChoiceFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM\ChoiceFilter', [
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

        /** @var ChoiceFilter|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getCustomMock('Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM\ChoiceFilter', [
            'checkDoctrineORMHandlerInstance',
        ]);

        $mock->expects($this->once())->method('checkDoctrineORMHandlerInstance')->with($handler);

        $mock->setValue([1, 2, 3]);
        $mock->setFieldName('foo');
        $mock->setName('bar');
        $mock->applyFilter($handler);
    }
}
