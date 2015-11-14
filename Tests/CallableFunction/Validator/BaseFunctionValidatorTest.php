<?php

namespace Da2e\FiltrationBundle\Tests\CallableFunction\Validator;

use Da2e\FiltrationBundle\CallableFunction\Validator\BaseFunctionValidator;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class BaseFunctionValidatorTest
 * @package Da2e\FiltrationBundle\Tests\CallableFunction\Validator
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
            $noTypeHint,
            $omit
        ) {
        });

        $this->setPrivatePropertyValue($baseFunctionValidator, 'argumentTypes', [
            ['type' => 'stdClass'],
            ['type' => null, 'array' => true],
            ['type' => null],
            ['omit' => true],
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
            ['omit' => true],
            ['omit' => true],
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
}
