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
    protected $argumentTypes = array(
        // Abstract filter object.
        0 => array(
            'type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'
        )
    );
}
