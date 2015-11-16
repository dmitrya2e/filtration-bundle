<?php

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

/**
 * Class HasAppliedValueFunctionValidator
 * @package Da2e\FiltrationBundle\CallableFunction\Validator
 */
class HasAppliedValueFunctionValidator extends BaseFunctionValidator implements CallableFunctionValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    protected $argumentTypes = [
        ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
    ];
}
