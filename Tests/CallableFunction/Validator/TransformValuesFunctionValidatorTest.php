<?php

namespace Da2e\FiltrationBundle\Tests\CallableFunction\Validator;

use Da2e\FiltrationBundle\CallableFunction\Validator\TransformValuesFunctionValidator;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class TransformValuesFunctionValidatorTest
 * @package Da2e\FiltrationBundle\Tests\CallableFunction\Validator
 */
class TransformValuesFunctionValidatorTest extends TestCase
{
    public function testExtendsAndInterfaces()
    {
        $functionValidator = new TransformValuesFunctionValidator(function () {
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
        $functionValidator = new TransformValuesFunctionValidator(function () {
        });

        $result = $this->getPrivateProperty($functionValidator, 'argumentTypes')->getValue($functionValidator);

        $this->assertSame([
            ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
        ], $result);
    }

    public function testIsValid()
    {
        $functionValidator = new TransformValuesFunctionValidator(function (FilterInterface $filter) {
        });

        $this->assertTrue($functionValidator->isValid());
    }

    public function testIsValid_False()
    {
        $functionValidator = new TransformValuesFunctionValidator(function (AbstractFilter $filter) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }
}
