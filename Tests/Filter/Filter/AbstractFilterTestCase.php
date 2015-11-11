<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter;

use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class AbstractFilterTestCase
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter
 */
class AbstractFilterTestCase extends TestCase
{
    /**
     * Gets form builder mock.
     *
     * @param bool|array|null $methods         False for no method mocking
     * @param bool|array      $constructorArgs False to disable original constructor
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Form\FormBuilderInterface
     */
    protected function getFormBuilderMock($methods = null, $constructorArgs = false)
    {
        return $this->getCustomMock('Symfony\Component\Form\FormBuilder', $methods, $constructorArgs);
    }

    /**
     * Gets abstract filter mock.
     *
     * @param string          $className
     * @param bool|array|null $methods         False for no method mocking
     * @param bool|array      $constructorArgs False to disable original constructor
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractFilter
     */
    protected function getAbstractFilterMock($className, $methods = null, $constructorArgs = ['name'])
    {
        return $this->getCustomAbstractMock($className, $methods, $constructorArgs);
    }
}
