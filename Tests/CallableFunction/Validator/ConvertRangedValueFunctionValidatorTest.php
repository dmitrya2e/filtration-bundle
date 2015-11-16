<?php

namespace Da2e\FiltrationBundle\Tests\CallableFunction\Validator;

use Da2e\FiltrationBundle\CallableFunction\Validator\ConvertRangedValueFunctionValidator;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class ConvertRangedValueFunctionValidatorTest
 * @package Da2e\FiltrationBundle\Tests\CallableFunction\Validator
 */
class ConvertRangedValueFunctionValidatorTest extends TestCase
{
    public function testExtendsAndInterfaces()
    {
        $functionValidator = new ConvertRangedValueFunctionValidator(function () {
        });

        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\CallableFunction\Validator\BaseFunctionValidator',
            $functionValidator
        );

        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\CallableFunction\Validator\CallableFunctionValidatorInterface',
            $functionValidator
        );
    }

    public function testProperty_ArgumentTypes()
    {
        $functionValidator = new ConvertRangedValueFunctionValidator(function () {
        });

        $result = $this->getPrivateProperty($functionValidator, 'argumentTypes')->getValue($functionValidator);

        $this->assertSame([
            ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
            ['omit' => true],
            ['omit' => true],
        ], $result);
    }

    public function testIsValid()
    {
        $functionValidator = new ConvertRangedValueFunctionValidator(function (FilterInterface $filter, $valueFrom, $valueTo) {
        });

        $this->assertTrue($functionValidator->isValid());
    }

    public function testIsValid_False_FirstArgumentInvalid()
    {
        $functionValidator = new ConvertRangedValueFunctionValidator(function (AbstractFilter $filter, $valueFrom, $valueTo) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }

    public function testIsValid_False_NoSecondArgument()
    {
        $functionValidator = new ConvertRangedValueFunctionValidator(function (FilterInterface $filter) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }

    public function testIsValid_False_NoThirdArgument()
    {
        $functionValidator = new ConvertRangedValueFunctionValidator(function (FilterInterface $filter, $valueFrom) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }
}