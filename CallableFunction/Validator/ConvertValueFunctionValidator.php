<?php

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

/**
 * Class ConvertValueFunctionValidator
 * @package Da2e\FiltrationBundle\CallableFunction\Validator
 */
class ConvertValueFunctionValidator extends BaseFunctionValidator implements CallableFunctionValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    protected $argumentTypes = [
        ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
        // raw value
        ['omit' => true],
    ];
}
