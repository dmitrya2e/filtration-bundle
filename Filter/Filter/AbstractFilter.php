<?php

namespace Da2e\FiltrationBundle\Filter\Filter;

use Da2e\FiltrationBundle\CallableFunction\Validator\AppendFormFieldsFunctionValidator;
use Da2e\FiltrationBundle\CallableFunction\Validator\ApplyFiltersFunctionValidator;
use Da2e\FiltrationBundle\CallableFunction\Validator\TransformValuesFunctionValidator;
use Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException;
use Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException;
use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * A base abstract class with maximum functionality for bundles all default filters.
 * The class has all possible capabilities:
 *  - option setting
 *  - form representation
 *  - custom callable functions
 *
 * If you want to make your own custom filter, for convenience you can extend this class.
 * However, if you find that it takes too much functions, which you do not actually need,
 * you are free to not use it and the only thing that is essentially important, is to implement FilterInterface.
 *
 * @package Da2e\FiltrationBundle\Filter\Filter
 * @abstract
 */
abstract class AbstractFilter implements
    FilterInterface,
    FilterOptionInterface,
    FilterHasFormInterface,
    CustomApplyFilterInterface,
    CustomAppendFormFieldsInterface,
    CustomTransformValuesInterface
{
    /**
     * Internal name of the filter:
     *  - used in form name assignment
     *  - the filter may be retrieved from collection by this name
     *
     * @var string
     */
    protected $name;

    /**
     * The name of the field in external data sources:
     *  - raw MySQL
     *  - Doctrine ORM/MongoDB mapped property
     *  - ...
     *
     * @var string
     */
    protected $fieldName = '';

    /**
     * A title of the filter (used as a label of the form).
     *
     * @var string Empty by default
     */
    protected $title = '';

    /**
     * Form representation options.
     *
     * @var array $formOptions An empty array by default
     */
    protected $formOptions = [];

    /**
     * Defines if the filter has form representation.
     *
     * @var bool True by default
     */
    protected $hasForm = true;

    /**
     * The name of the form field type (makes sense to set only if a filter has a form).
     *
     * **Must be set in child classes.**
     *
     * @var string
     */
    protected $formFieldType = '';

    /**
     * Custom applyFilter() function.
     * Should be the type of "callable".
     *
     * @var null|callable Null by default
     */
    protected $applyFilterFunction = null;

    /**
     * Custom appendFormFields() function.
     * Should be the type of "callable".
     *
     * @var null|callable Null by default
     */
    protected $appendFormFieldsFunction = null;

    /**
     * Custom function for value transformation.
     * Should be the type of "callable".
     *
     * @var null|callable Null by default
     */
    protected $transformValuesFunction = null;

    /**
     * Filter applied raw value (set by form, for example, or manually):
     *  - string
     *  - Collection
     *  - Entity
     *  - etc
     *
     * The value type depends on specific Filter.
     * **This value must not be used for filtration. See AbstractFilter::$convertedValue.**
     *
     * @see AbstractFilter::$convertedValue
     * @var mixed|null Null by default
     */
    protected $value = null;

    /**
     * The default value for the filter (used in form "data" key as well).
     *
     * @var mixed|null Null by default
     */
    protected $defaultValue = null;

    /**
     * The value which is converted by raw value.
     * This is the "clean" and workable form of value, which must be used for filtration.
     *
     * The value is being converted while executing method AbstractFilter::convertValue().
     *
     * @see AbstractFilter::convertValue()
     * @var mixed|null Null by default
     */
    protected $convertedValue = null;

    /**
     * A flag which defines if the value has been already converted, just to prevent double conversion.
     *
     * @var bool False by default
     */
    protected $valueHasBeenConverted = false;

    /**
     * The default name of the property containing raw value (defaults to AbstractFilter::$value).
     *
     * @var string "value" by default
     */
    protected $valuePropertyName = 'value';

    /**
     * @param null|string $name Filter name
     */
    public function __construct($name = null)
    {
        $this->setName($name);

        // Configure the filter before any other work is done.
        $this->configure();
    }

    /**
     * {@inheritDoc}
     */
    public static function getValidOptions()
    {
        return [
            // Base options
            'name'                        => [
                'setter' => 'setName',
                'empty'  => false,
                'type'   => 'string',
            ],
            'field_name'                  => [
                'setter' => 'setFieldName',
                'empty'  => false,
                'type'   => 'string',
            ],
            'default_value'               => [
                'setter' => 'setDefaultValue',
                'empty'  => true,
            ],
            'value_property_name'         => [
                'setter' => 'setValuePropertyName',
                'empty'  => false,
                'type'   => 'string',
            ],
            // Form related options
            'title'                       => [
                'setter' => 'setTitle',
                'empty'  => true,
                'type'   => 'string',
            ],
            'form_options'                => [
                'setter' => 'setFormOptions',
                'empty'  => true,
                'type'   => 'array',
            ],
            'has_form'                    => [
                'setter' => 'setHasForm',
                'empty'  => false,
                'type'   => 'bool',
            ],
            'form_field_type'             => [
                'setter' => 'setFormFieldType',
                'empty'  => false,
                'type'   => 'string',
            ],
            // Custom callable functions
            'apply_filter_function'       => [
                'setter' => 'setApplyFilterFunction',
                'empty'  => false,
                'type'   => 'callable',
            ],
            'append_form_fields_function' => [
                'setter' => 'setAppendFormFieldsFunction',
                'empty'  => false,
                'type'   => 'callable',
            ],
            'transform_values_function'   => [
                'setter' => 'setTransformValuesFunction',
                'empty'  => false,
                'type'   => 'callable',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @abstract
     */
    abstract public function applyFilter($handler);

    /**
     * {@inheritDoc}
     *
     * @abstract
     */
    abstract public function getType();

    /**
     * Converts raw value into appropriate form to work with.
     * The converted value must be returned by the method.
     *
     * @return mixed The converted value
     * @abstract
     */
    abstract protected function convertValue();

    /**
     * {@inheritDoc}
     *
     * @throws FilterException if was not implemented in child filter
     */
    public function appendFormFieldsToForm(FormBuilderInterface $formBuilder)
    {
        throw new FilterException('You must implement this method in child classes.');
    }

    /**
     * Checks if the filter value was applied.
     * Note, that the converted value is used in checking.
     *
     * @see AbstractFilter::getConvertedValue()
     * @return bool
     */
    public function hasAppliedValue()
    {
        $convertedValue = $this->getConvertedValue();

        if (is_array($convertedValue)) {
            return count($convertedValue) > 0;
        }

        if (is_string($convertedValue)) {
            return $convertedValue !== '';
        }

        // Default fallback to empty() function.
        // If the value is int/float and it is equal to 0, it will be considered that the value has not been applied.
        // To change this behaviour, please override this method.
        return !empty($convertedValue);
    }

    /**
     * Gets the converted value for specific filter.
     * If the values has not been converted yet, the AbstractFilter::convertValue() method will be executed.
     *
     * @see AbstractFilter::convertValue()
     * @return mixed
     */
    public function getConvertedValue()
    {
        if ($this->valueHasBeenConverted === false) {
            $this->convertedValue = $this->convertValue();
            $this->valueHasBeenConverted = true;
        }

        return $this->convertedValue;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setName($name)
    {
        if (!is_string($name) || $name === '') {
            throw new InvalidArgumentException('"Name" argument must be a string and must not empty.');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Gets the field name for the external data source of the filter.
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setFieldName($fieldName)
    {
        if (!is_string($fieldName) || $fieldName === '') {
            throw new InvalidArgumentException('"Field name" argument must be a string and must not empty.');
        }

        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Gets the title of the filter.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the filter.
     *
     * @param string $title
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setTitle($title)
    {
        if (!is_string($title)) {
            throw new InvalidArgumentException('"Title" argument must be a string.');
        }

        $this->title = $title;

        return $this;
    }

    /**
     * Sets form options.
     * The option array is fully compatible with Symfony form options.
     *
     * @param array $formOptions
     *
     * @return static
     */
    public function setFormOptions(array $formOptions)
    {
        $this->formOptions = $formOptions;

        return $this;
    }

    /**
     * Gets form options.
     *
     * @return array
     */
    public function getFormOptions()
    {
        return $this->formOptions;
    }

    /**
     * Sets custom "transform values" function.
     *
     * @param callable $function
     *
     * @see TransformValuesFunctionValidator
     *
     * @return static
     * @throws CallableFunctionValidatorException On invalid callable arguments
     */
    public function setTransformValuesFunction(callable $function)
    {
        $validator = new TransformValuesFunctionValidator($function);

        if ($validator->isValid() === false) {
            throw $validator->getException();
        }

        $this->transformValuesFunction = $function;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return null|callable
     */
    public function getTransformValuesFunction()
    {
        return $this->transformValuesFunction;
    }

    /**
     * Sets custom "apply filter" function.
     *
     * @param callable $function
     *
     * @see ApplyFiltersFunctionValidator
     *
     * @return static
     * @throws CallableFunctionValidatorException On invalid callable arguments
     */
    public function setApplyFilterFunction(callable $function)
    {
        $validator = new ApplyFiltersFunctionValidator($function);

        if ($validator->isValid() === false) {
            throw $validator->getException();
        }

        $this->applyFilterFunction = $function;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return null|callable
     */
    public function getApplyFilterFunction()
    {
        return $this->applyFilterFunction;
    }

    /**
     * Sets custom "append form fields" function.
     *
     * @param callable $function
     *
     * @see AppendFormFieldsFunctionValidator
     *
     * @return static
     * @throws CallableFunctionValidatorException On invalid callable arguments
     */
    public function setAppendFormFieldsFunction(callable $function)
    {
        $validator = new AppendFormFieldsFunctionValidator($function);

        if ($validator->isValid() === false) {
            throw $validator->getException();
        }

        $this->appendFormFieldsFunction = $function;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return null|callable
     */
    public function getAppendFormFieldsFunction()
    {
        return $this->appendFormFieldsFunction;
    }

    /**
     * Defines if the filter has a form representation.
     *
     * @param boolean $hasForm
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setHasForm($hasForm)
    {
        if (!is_bool($hasForm)) {
            throw new InvalidArgumentException('"Has form" argument must be boolean.');
        }

        $this->hasForm = $hasForm;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return boolean
     */
    public function hasForm()
    {
        return $this->hasForm;
    }

    /**
     * Gets the raw value of the filter.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the raw value of the filter (via form or manually).
     *
     * @param mixed $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Sets the default value of the filter.
     *
     * @param mixed $value
     *
     * @return static
     */
    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;

        return $this;
    }

    /**
     * Gets the default value of the filter.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets the name of the property which contains the raw value.
     *
     * @param string $valuePropertyName
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setValuePropertyName($valuePropertyName)
    {
        if (!is_string($valuePropertyName) || $valuePropertyName === '') {
            throw new InvalidArgumentException('"Value property name" argument must be a string and must not empty.');
        }

        if (!property_exists($this, $valuePropertyName)) {
            throw new InvalidArgumentException(sprintf('Property "%s" does not exist.', $valuePropertyName));
        }

        $this->valuePropertyName = $valuePropertyName;

        return $this;
    }

    /**
     * Gets the name of the property which contains the raw value.
     *
     * @return string
     */
    public function getValuePropertyName()
    {
        return $this->valuePropertyName;
    }

    /**
     * Gets form field type.
     *
     * @return string
     */
    public function getFormFieldType()
    {
        return $this->formFieldType;
    }

    /**
     * Sets form field type.
     *
     * @param string $formFieldType
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setFormFieldType($formFieldType)
    {
        if (!is_string($formFieldType) || $formFieldType === '') {
            throw new InvalidArgumentException('"Form field type" argument must be a string and must not empty.');
        }

        $this->formFieldType = $formFieldType;

        return $this;
    }

    /**
     * Configures filter (being executed in filter constructor).
     * If something needs to be configured before any work is done, it can be achieved via this method.
     *
     * @return $this
     */
    protected function configure()
    {
        return $this;
    }
}
