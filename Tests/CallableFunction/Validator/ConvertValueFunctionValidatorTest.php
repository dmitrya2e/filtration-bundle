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

use Da2e\FiltrationBundle\CallableFunction\Validator\ConvertValueFunctionValidator;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class ConvertValueFunctionValidatorTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class ConvertValueFunctionValidatorTest extends TestCase
{
    public function testExtendsAndInterfaces()
    {
        $functionValidator = new ConvertValueFunctionValidator(function () {
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
        $functionValidator = new ConvertValueFunctionValidator(function () {
        });

        $result = $this->getPrivateProperty($functionValidator, 'argumentTypes')->getValue($functionValidator);

        $this->assertSame([
            ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
        ], $result);
    }

    public function testIsValid()
    {
        $functionValidator = new ConvertValueFunctionValidator(function (FilterInterface $filter) {
        });

        $this->assertTrue($functionValidator->isValid());
    }

    public function testIsValid_False()
    {
        $functionValidator = new ConvertValueFunctionValidator(function (AbstractFilter $filter) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }
}
