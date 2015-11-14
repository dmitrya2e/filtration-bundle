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
    protected $argumentTypes = [
        ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
        // Filtration handler object, which does not have strict type hint.
        ['omit' => true],
    ];
}
