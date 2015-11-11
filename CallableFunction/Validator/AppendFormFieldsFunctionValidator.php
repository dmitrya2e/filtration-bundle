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
    protected $argumentTypes = array(
        // Abstract filter object.
        0 => array(
            'type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'
        ),
        // Form object.
        1 => array(
            'type' => 'Symfony\Component\Form\FormBuilderInterface'
        )
    );
}
