<?php

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

/**
 * Class AppendFormFieldsFunctionValidator
 * @package Da2e\FiltrationBundle\CallableFunction\Validator
 */
class AppendFormFieldsFunctionValidator extends BaseFunctionValidator
{
    /**
     * {@inheritdoc}
     */
    protected $argumentTypes = [
        ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
        ['type' => 'Symfony\Component\Form\FormBuilderInterface'],
    ];
}
