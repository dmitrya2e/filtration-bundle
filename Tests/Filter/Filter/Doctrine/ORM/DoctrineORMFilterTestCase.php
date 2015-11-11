<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Doctrine\ORM;

use Da2e\FiltrationBundle\Tests\Filter\Filter\AbstractFilterTestCase;

/**
 * Class DoctrineORMFilterTestCase
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter\Doctrine\ORM
 */
class DoctrineORMFilterTestCase extends AbstractFilterTestCase
{
    /**
     * Gets Doctrine ORM query builder mock.
     *
     * @param bool|array|null $methods
     * @param bool|array|null $constructorArgs
     *
     * @return \Doctrine\ORM\QueryBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getDoctrineORMQueryBuilderMock($methods = ['andWhere', 'setParameter'], $constructorArgs = false)
    {
        return $this->getCustomMock('\Doctrine\ORM\QueryBuilder', $methods, $constructorArgs);
    }
}
