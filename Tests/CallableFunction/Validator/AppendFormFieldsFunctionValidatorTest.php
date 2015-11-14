<?php

namespace Da2e\FiltrationBundle\Tests\CallableFunction\Validator;

use Da2e\FiltrationBundle\CallableFunction\Validator\AppendFormFieldsFunctionValidator;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Tests\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class AppendFormFieldsFunctionValidatorTest
 * @package Da2e\FiltrationBundle\Tests\CallableFunction\Validator
 */
class AppendFormFieldsFunctionValidatorTest extends TestCase
{
    public function testExtendsAndInterfaces()
    {
        $functionValidator = new AppendFormFieldsFunctionValidator(function () {
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
        $functionValidator = new AppendFormFieldsFunctionValidator(function () {
        });

        $result = $this->getPrivateProperty($functionValidator, 'argumentTypes')->getValue($functionValidator);

        $this->assertSame([
            ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
            ['type' => 'Symfony\Component\Form\FormBuilderInterface'],
        ], $result);
    }

    public function testIsValid()
    {
        $functionValidator = new AppendFormFieldsFunctionValidator(function (
            FilterInterface $filter,
            FormBuilderInterface $form
        ) {
        });

        $this->assertTrue($functionValidator->isValid());
    }

    public function testIsValid_False_FilterArgumentInvalid()
    {
        $functionValidator = new AppendFormFieldsFunctionValidator(function (
            AbstractFilter $filter,
            FormBuilderInterface $form
        ) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }

    public function testIsValid_False_FormArgumentInvalid()
    {
        $functionValidator = new AppendFormFieldsFunctionValidator(function (
            FilterInterface $filter,
            FormInterface $form
        ) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }

    public function testIsValid_False_InvalidArgumentCount()
    {
        $functionValidator = new AppendFormFieldsFunctionValidator(function (FilterInterface $filter) {
        });

        $this->assertFalse($functionValidator->isValid());
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException',
            $functionValidator->getException()
        );
    }
}
