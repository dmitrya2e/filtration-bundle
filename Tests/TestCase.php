<?php

namespace Da2e\FiltrationBundle\Tests;

/**
 * Class TestCase
 * @package Da2e\FiltrationBundle\Tests
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod($object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * getPrivateProperty
     *
     * @author    Joe Sexton <joe@webtipblog.com>
     *
     * @param    string $className
     * @param    string $propertyName
     *
     * @return    \ReflectionProperty
     */
    protected function getPrivateProperty($className, $propertyName)
    {
        $reflector = new \ReflectionClass($className);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * Convenient overrided getMockForAbstractClass() method.
     *
     * @param string          $className
     * @param bool|array|null $methods         False for no method mocking
     * @param bool|array      $constructorArgs False to disable original constructor
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCustomAbstractMock($className, $methods = null, $constructorArgs = false)
    {
        return $this->getCustomMockBuilder($className, $methods, $constructorArgs)->getMockForAbstractClass();
    }

    /**
     * Convenient overrided getMock() method.
     *
     * @param string          $className
     * @param bool|array|null $methods         False for no method mocking
     * @param bool|array      $constructorArgs False to disable original constructor
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCustomMock($className, $methods = null, $constructorArgs = false)
    {
        return $this->getCustomMockBuilder($className, $methods, $constructorArgs)->getMock();
    }

    /**
     * Gets mock builder.
     * Convenient overrided method for getting MockBuilder object.
     *
     * @param string          $className
     * @param bool|array|null $methods         False for no method mocking
     * @param bool|array      $constructorArgs False to disable original constructor
     *
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     */
    private function getCustomMockBuilder($className, $methods = null, $constructorArgs = false)
    {
        $mockBuilder = $this->getMockBuilder($className);

        if ($constructorArgs === false) {
            $mockBuilder->disableOriginalConstructor();
        } elseif (is_array($constructorArgs)) {
            $mockBuilder->setConstructorArgs($constructorArgs);
        }

        if ($methods !== false && (is_array($methods) || $methods === null)) {
            $mockBuilder->setMethods($methods);
        }

        return $mockBuilder;
    }
}
