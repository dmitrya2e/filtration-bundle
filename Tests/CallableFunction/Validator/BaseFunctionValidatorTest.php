<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\CallableFunction\Validator;

use Da2e\FiltrationBundle\CallableFunction\Validator\BaseFunctionValidator;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class BaseFunctionValidatorTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class BaseFunctionValidatorTest extends TestCase
{
    public function testIsValid_FalseBecauseNoArgumentsAreDescribed()
    {
        $baseFunctionValidator = new BaseFunctionValidator(function () {
        });

        $this->assertFalse($baseFunctionValidator->isValid());
        $this->assertInstanceOf('\Exception', $baseFunctionValidator->getException());
    }

    public function testGetException()
    {
        $baseFunctionValidator = new BaseFunctionValidator(function () {
        });

        $this->assertNull($baseFunctionValidator->getException());

        // Init validation process, which will cause an exception throwing (because no arguments of callable function are described).
        $baseFunctionValidator->isValid();
        $this->assertInstanceOf('\Exception', $baseFunctionValidator->getException());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException
     */
    public function testDoValidate_ShouldThrowAnExceptionBecauseNoArgumentsAreDescribed()
    {
        $baseFunctionValidator = new BaseFunctionValidator(function () {
        });

        $this->invokeMethod($baseFunctionValidator, 'doValidate');
    }

    public function testDoValidate_ShouldNotThrowExceptions()
    {
        $baseFunctionValidator = new BaseFunctionValidator(function (
            \stdClass $object,
            array $array,
            $noTypeHint
        ) {
        });

        $this->setPrivatePropertyValue($baseFunctionValidator, 'argumentTypes', [
            ['type' => 'stdClass'],
            ['type' => null, 'array' => true],
            ['type' => null],
        ]);

        $this->invokeMethod($baseFunctionValidator, 'doValidate');
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException
     */
    public function testDoValidate_InvalidArgumentCount()
    {
        $baseFunctionValidator = new BaseFunctionValidator(function ($foo) {
        });

        $this->setPrivatePropertyValue($baseFunctionValidator, 'argumentTypes', [
            ['type' => null],
            ['type' => null],
        ]);

        $this->invokeMethod($baseFunctionValidator, 'doValidate');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDoValidate_TypeIsNotSetInArgumentDescription()
    {
        $baseFunctionValidator = new BaseFunctionValidator(function ($foo) {
        });

        $this->setPrivatePropertyValue($baseFunctionValidator, 'argumentTypes', [
            [],
        ]);

        $this->invokeMethod($baseFunctionValidator, 'doValidate');
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException
     */
    public function testDoValidate_InvalidType()
    {
        $baseFunctionValidator = new BaseFunctionValidator(function (FilterInterface $foo) {
        });

        $this->setPrivatePropertyValue($baseFunctionValidator, 'argumentTypes', [
            ['type' => 'stdClass'],
        ]);

        $this->invokeMethod($baseFunctionValidator, 'doValidate');
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException
     */
    public function testDoValidate_MustBeArrayButIsObject()
    {
        $baseFunctionValidator = new BaseFunctionValidator(function (FilterInterface $foo) {
        });

        $this->setPrivatePropertyValue($baseFunctionValidator, 'argumentTypes', [
            ['type' => null, 'array' => true],
        ]);

        $this->invokeMethod($baseFunctionValidator, 'doValidate');
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException
     */
    public function testDoValidate_MustBeArrayButIsNot()
    {
        $baseFunctionValidator = new BaseFunctionValidator(function ($foo) {
        });

        $this->setPrivatePropertyValue($baseFunctionValidator, 'argumentTypes', [
            ['type' => null, 'array' => true],
        ]);

        $this->invokeMethod($baseFunctionValidator, 'doValidate');
    }

    public function testSetCallableFunction()
    {
        $baseFunctionValidator = new BaseFunctionValidator();

        $func = $this->getPrivateProperty($baseFunctionValidator, 'callableFunction');
        $this->assertNull($func->getValue($baseFunctionValidator));

        $baseFunctionValidator->setCallableFunction(function () {
        });

        $func = $this->getPrivateProperty($baseFunctionValidator, 'callableFunction');
        $this->assertTrue(is_callable($func->getValue($baseFunctionValidator)));
    }
}
