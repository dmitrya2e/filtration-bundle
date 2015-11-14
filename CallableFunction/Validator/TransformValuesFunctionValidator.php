<?php

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

/**
 * Class TransformValuesFunctionValidator
 * @package Da2e\FiltrationBundle\CallableFunction\Validator
 */
class TransformValuesFunctionValidator extends BaseFunctionValidator
{
    /**
     * {@inheritdoc}
     */
    protected $argumentTypes = [
        ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
    ];
}
