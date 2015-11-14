<?php

namespace Da2e\FiltrationBundle\Tests\CallableFunction\Validator;

use Da2e\FiltrationBundle\CallableFunction\Validator\ApplyFiltersFunctionValidator;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class ApplyFiltersFunctionValidatorTest
 * @package Da2e\FiltrationBundle\Tests\CallableFunction\Validator
 */
class ApplyFiltersFunctionValidatorTest extends TestCase
{
    public function testProperty_ArgumentTypes()
    {
        $functionValidator = new ApplyFiltersFunctionValidator(function () {
        });

        $result = $this->getPrivateProperty($functionValidator, 'argumentTypes')->getValue($functionValidator);

        $this->assertSame([
            ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
            ['omit' => true],
        ], $result);
    }

    public function testIsValid()
    {
        $functionValidator = new ApplyFiltersFunctionValidator(function (FilterInterface $filter, $handler) {
        });

        $this->assertTrue($functionValidator->isValid());
    }

    public function testIsValid_False_FilterArgumentInvalid()
    {
        $functionValidator = new ApplyFiltersFunctionValidator(function (AbstractFilter $filter, $handler) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }

    public function testIsValid_False_NoHandlerSpecified()
    {
        $functionValidator = new ApplyFiltersFunctionValidator(function (FilterInterface $filter) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }
}
