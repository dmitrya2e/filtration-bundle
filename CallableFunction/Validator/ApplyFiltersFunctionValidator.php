<?php

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

/**
 * Class ApplyFiltersFunctionValidator
 * @package Da2e\FiltrationBundle\CallableFunction\Validator
 */
class ApplyFiltersFunctionValidator extends BaseFunctionValidator
{
    /**
     * {@inheritdoc}
     */
    protected $argumentTypes = array(
        // Abstract filter object.
        0 => array(
            'type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'
        ),
        // Filtration handler object. It may be not validated, because it is polymorphic.
        1 => array('omit' => true)
    );
}
