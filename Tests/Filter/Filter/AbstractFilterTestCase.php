<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter;

use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class AbstractFilterTestCase
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter
 */
class AbstractFilterTestCase extends TestCase
{
    /**
     * Gets form builder mock.
     *
     * @param bool|array|null $methods         False for no method mocking
     * @param bool|array      $constructorArgs False to disable original constructor
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Form\FormBuilderInterface
     */
    protected function getFormBuilderMock($methods = null, $constructorArgs = false)
    {
        return $this->getCustomMock('Symfony\Component\Form\FormBuilder', $methods, $constructorArgs);
    }

    /**
     * Gets abstract filter mock.
     *
     * @param string          $className
     * @param bool|array|null $methods         False for no method mocking
     * @param bool|array      $constructorArgs False to disable original constructor
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractFilter
     */
    protected function getAbstractFilterMock($className, $methods = null, $constructorArgs = ['name'])
    {
        return $this->getCustomAbstractMock($className, $methods, $constructorArgs);
    }

    /**
     * @return array
     */
    protected function getAbstractFilterValidOptions()
    {
        return [
            'name'                                  => [
                'setter' => 'setName',
                'empty'  => false,
                'type'   => 'string',
            ],
            'field_name'                            => [
                'setter' => 'setFieldName',
                'empty'  => false,
                'type'   => 'string',
            ],
            'default_value'                         => [
                'setter' => 'setDefaultValue',
                'empty'  => true,
            ],
            'value_property_name'                   => [
                'setter' => 'setValuePropertyName',
                'empty'  => false,
                'type'   => 'string',
            ],
            'title'                                 => [
                'setter' => 'setTitle',
                'empty'  => true,
                'type'   => 'string',
            ],
            'form_options'                          => [
                'setter' => 'setFormOptions',
                'empty'  => true,
                'type'   => 'array',
            ],
            'has_form'                              => [
                'setter' => 'setHasForm',
                'empty'  => false,
                'type'   => 'bool',
            ],
            'form_field_type'                       => [
                'setter' => 'setFormFieldType',
                'empty'  => false,
                'type'   => 'string',
            ],
            'apply_filter_function'                 => [
                'setter' => 'setApplyFilterFunction',
                'empty'  => false,
                'type'   => 'callable',
            ],
            'append_form_fields_function'           => [
                'setter' => 'setAppendFormFieldsFunction',
                'empty'  => false,
                'type'   => 'callable',
            ],
            'has_applied_value_function'            => [
                'setter' => 'setHasAppliedValueFunction',
                'empty'  => false,
                'type'   => 'callable',
            ],
            'convert_value_function'            => [
                'setter' => 'setConvertValueFunction',
                'empty'  => false,
                'type'   => 'callable',
            ],
            'callable_validator_apply_filter'       => [
                'setter'      => 'setCallableValidatorApplyFilter',
                'empty'       => false,
                'type'        => 'object',
                'instance_of' => '\Da2e\FiltrationBundle\CallableFunction\Validator\CallableFunctionValidatorInterface',
            ],
            'callable_validator_append_form_fields' => [
                'setter'      => 'setCallableValidatorAppendFormFields',
                'empty'       => false,
                'type'        => 'object',
                'instance_of' => '\Da2e\FiltrationBundle\CallableFunction\Validator\CallableFunctionValidatorInterface',
            ],
            'callable_validator_has_applied_value'  => [
                'setter'      => 'setCallableValidatorHasAppliedValue',
                'empty'       => false,
                'type'        => 'object',
                'instance_of' => '\Da2e\FiltrationBundle\CallableFunction\Validator\CallableFunctionValidatorInterface',
            ],
            'callable_validator_convert_value'  => [
                'setter'      => 'setCallableValidatorConvertValue',
                'empty'       => false,
                'type'        => 'object',
                'instance_of' => '\Da2e\FiltrationBundle\CallableFunction\Validator\CallableFunctionValidatorInterface',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getAbstractRangeOrSingleFilterValidOptions()
    {
        return array_merge($this->getAbstractFilterValidOptions(), [
            'from_postfix'                => [
                'setter' => 'setFromPostfix',
                'empty'  => false,
                'type'   => 'string',
            ],
            'to_postfix'                  => [
                'setter' => 'setToPostfix',
                'empty'  => false,
                'type'   => 'string',
            ],
            'single'                      => [
                'setter' => 'setSingle',
                'empty'  => false,
                'type'   => 'bool',
            ],
            'single_type'                 => [
                'setter' => 'setSingleType',
                'empty'  => false,
                'type'   => 'string',
            ],
            'ranged_from_type'            => [
                'setter' => 'setRangedFromType',
                'empty'  => false,
                'type'   => 'string',
            ],
            'ranged_to_type'              => [
                'setter' => 'setRangedToType',
                'empty'  => false,
                'type'   => 'string',
            ],
            'from_value_property_name'    => [
                'setter' => 'setFromValuePropertyName',
                'empty'  => false,
                'type'   => 'string',
            ],
            'to_value_property_name'      => [
                'setter' => 'setToValuePropertyName',
                'empty'  => false,
                'type'   => 'string',
            ],
            'form_field_type_ranged_from' => [
                'setter' => 'setFormFieldTypeRangedFrom',
                'empty'  => false,
                'type'   => 'string',
            ],
            'form_field_type_ranged_to'   => [
                'setter' => 'setFormFieldTypeRangedTo',
                'empty'  => false,
                'type'   => 'string',
            ],
        ]);
    }
}
