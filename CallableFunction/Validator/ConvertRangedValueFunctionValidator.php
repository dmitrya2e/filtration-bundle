<?php

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

/**
 * Class ConvertRangedValueFunctionValidator
 * @package Da2e\FiltrationBundle\CallableFunction\Validator
 */
class ConvertRangedValueFunctionValidator extends BaseFunctionValidator implements CallableFunctionValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    protected $argumentTypes = [
        ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
    ];
}
