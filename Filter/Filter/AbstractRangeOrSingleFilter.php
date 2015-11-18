<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Filter;

use Da2e\FiltrationBundle\CallableFunction\Validator\CallableFunctionValidatorInterface;
use Da2e\FiltrationBundle\CallableFunction\Validator\ConvertRangedValueFunctionValidator;
use Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException;
use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException;

/**
 * Base abstract "range or single" filter class.
 * This class is useful for filters, which can act either as "single" filter or "ranged" filter. For example:
 *  - number filter can execute filtration by exact number (single ("WHERE `field` = $value"))
 *  - number filter can execute filtration by range of two numbers (ranged ("WHERE `field` > $min AND `field` < $max"))
 *
 * Again, this is just an abstraction and it is optional,
 * however it is convenient to use this class for such types of filters.
 *
 * Anyway, you are free to choose whether to use it or not,
 * because the only thing that really matters is to implement a FilterInterface.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 * @abstract
 */
abstract class AbstractRangeOrSingleFilter extends AbstractFilter
{
    // Filtration types for single filter.
    /**
     * Logical operator "=".
     *
     * @const
     */
    const SINGLE_TYPE_EXACT = 'single_exact';

    /**
     * Logical operator ">".
     *
     * @const
     */
    const SINGLE_TYPE_GREATER = 'single_greater';

    /**
     * Logical operator ">=".
     *
     * @const
     */
    const SINGLE_TYPE_GREATER_OR_EQUAL = 'single_greater_or_equal';

    /**
     * Logical operator "<".
     *
     * @const
     */
    const SINGLE_TYPE_LESS = 'less';

    /**
     * Logical operator "<=".
     *
     * @const
     */
    const SINGLE_TYPE_LESS_OR_EQUAL = 'single_less_or_equal';

    // Filtration types for ranged filter.
    /**
     * Logical operator ">";
     *
     * @const
     */
    const RANGED_FROM_TYPE_GREATER = 'ranged_from_greater';

    /**
     * Logical operator ">=";
     *
     * @const
     */
    const RANGED_FROM_TYPE_GREATER_OR_EQUAL = 'ranged_from_greater_or_equal';

    /**
     * Logical operator "<";
     *
     * @const
     */
    const RANGED_TO_TYPE_LESS = 'ranged_to_less';

    /**
     * Logical operator "<=";
     *
     * @const
     */
    const RANGED_TO_TYPE_LESS_OR_EQUAL = 'ranged_to_less_or_equal';

    /**
     * The "from" raw value of the filter.
     * Used only for "ranged" type.
     *
     * @var mixed|null Null by default
     */
    protected $fromValue = null;

    /**
     * The "to" raw value of the filter.
     * Used only for "ranged" type.
     *
     * @var mixed|null Null by default
     */
    protected $toValue = null;

    /**
     * The "from" converted value of the filter.
     * Used only for "ranged" type.
     *
     * @var mixed|null Null by default
     */
    protected $convertedFromValue = null;

    /**
     * The "to" converted value of the filter.
     * Used only for "ranged" type.
     *
     * @var mixed|null Null by default
     */
    protected $convertedToValue = null;

    /**
     * The "from" postfix, which is for identifying the ranged field (form field as well) "from".
     * Used only for "ranged" type.
     *
     * @var string "_from" by default
     */
    protected $fromPostfix = '_from';

    /**
     * The "to" postfix, which is for identifying the ranged field (form field as well) "to".
     * Used only for "ranged" type.
     *
     * @var string "_to" by default
     */
    protected $toPostfix = '_to';

    /**
     * Treat filter as single or range.
     *
     * @var bool False by default (treat as range)
     */
    protected $single = false;

    /**
     * Default filtration type for "single" type.
     *
     * @var string AbstractRangeOrSingleFilter::SINGLE_TYPE_EXACT by default
     */
    protected $singleType = self::SINGLE_TYPE_EXACT;

    /**
     * Default filtration type for "ranged" type for "from" field.
     * Used only for "ranged" type.
     *
     * @var string AbstractRangeOrSingleFilter::RANGED_FROM_TYPE_GREATER_OR_EQUAL by default
     */
    protected $rangedFromType = self::RANGED_FROM_TYPE_GREATER_OR_EQUAL;

    /**
     * Default filtration type for "ranged" type for "to" field.
     * Used only for "ranged" type.
     *
     * @var string AbstractRangeOrSingleFilter::RANGED_TO_TYPE_LESS_OR_EQUAL by default
     */
    protected $rangedToType = self::RANGED_TO_TYPE_LESS_OR_EQUAL;

    /**
     * The default name of the property containing raw value of "from" field
     * Defaults to AbstractRangeOrSingleFilter::$fromValue.
     * Used only for "ranged" type.
     *
     * @var string "fromValue" by default
     */
    protected $fromValuePropertyName = 'fromValue';

    /**
     * The default name of the property containing raw value of "to" field
     * Defaults to AbstractRangeOrSingleFilter::$toValue.
     * Used only for "ranged" type.
     *
     * @var string "toValue" by default
     */
    protected $toValuePropertyName = 'toValue';

    /**
     * Form field type name for the "from" field for the ranged mode.
     * Used only for "ranged" type.
     *
     * **Must be set in child classes.**
     *
     * @var string Empty by default
     */
    protected $formFieldTypeRangedFrom = '';

    /**
     * Form field type name for the "to" field for the ranged mode.
     * Used only for "ranged" type.
     *
     * **Must be set in child classes.**
     *
     * @var string Empty by default
     */
    protected $formFieldTypeRangedTo = '';

    /**
     * Converts single value (AbstractRangeOrSingleFilter::$single = true).
     *
     * @see AbstractFilter::convertValue()
     *
     * @return mixed
     */
    abstract protected function convertSingleValue();

    /**
     * Converts ranged value (AbstractRangeOrSingleFilter::$single = false).
     *
     * @see AbstractFilter::convertValue()
     *
     * @return mixed
     */
    abstract protected function convertRangedValue();

    /**
     * {@inheritDoc}
     */
    public static function getValidOptions()
    {
        return array_merge(parent::getValidOptions(), [
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

    /**
     * {@inheritDoc}
     */
    protected function convertValue()
    {
        if ($this->isSingle() === true) {
            return $this->convertSingleValue();
        }

        return $this->convertRangedValue();
    }

    /**
     * {@inheritDoc}
     *
     * @throws FilterException On invalid custom function returned value
     */
    public function hasAppliedValue()
    {
        $customFunction = $this->getHasAppliedValueFunction();

        if (is_callable($customFunction)) {
            $result = call_user_func($customFunction, $this);

            if (!is_bool($result)) {
                throw new FilterException('Returned value from callable function must be boolean.');
            }

            return $result;
        }

        if ($this->isSingle() === false) {
            return $this->getConvertedFromValue() !== null || $this->getConvertedToValue() !== null;
        }

        return $this->getConvertedValue() !== null;
    }

    /**
     * Sets the raw value for the "from" field in the ranged mode.
     *
     * @param mixed $fromValue
     *
     * @return static
     */
    public function setFromValue($fromValue)
    {
        $this->fromValue = $fromValue;

        return $this;
    }

    /**
     * Get the raw value of the "from" field.
     *
     * @return mixed
     */
    public function getFromValue()
    {
        return $this->fromValue;
    }

    /**
     * Sets the raw value for the "to" field in the ranged mode.
     *
     * @param mixed $toValue
     *
     * @return static
     */
    public function setToValue($toValue)
    {
        $this->toValue = $toValue;

        return $this;
    }

    /**
     * Get the raw value of the "from" field.
     *
     * @return mixed|null
     */
    public function getToValue()
    {
        return $this->toValue;
    }

    /**
     * Gets converted "from" value (for the ranged mode).
     * If the value was not yet converted, than AbstractFilter::executeValueConversion() will be executed.
     *
     * @return mixed|null
     */
    public function getConvertedFromValue()
    {
        if ($this->valueHasBeenConverted === false) {
            $value = $this->executeValueConversion();

            $this->convertedFromValue = $value[0];
            $this->convertedToValue = $value[1];

            $this->valueHasBeenConverted = true;
        }

        return $this->convertedFromValue;
    }

    /**
     * Gets converted "to" value (for the ranged mode).
     * If the value was not yet converted, than AbstractFilter::executeValueConversion() will be executed.
     *
     * @return mixed|null
     */
    public function getConvertedToValue()
    {
        if ($this->valueHasBeenConverted === false) {
            $value = $this->executeValueConversion();

            $this->convertedFromValue = $value[0];
            $this->convertedToValue = $value[1];

            $this->valueHasBeenConverted = true;
        }

        return $this->convertedToValue;
    }

    /**
     * Defines if the filter must be treated as single or ranged.
     *
     * @param bool $single True for single treatment
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setSingle($single)
    {
        if (!is_bool($single)) {
            throw new InvalidArgumentException('"Single" argument must be boolean.');
        }

        $this->single = $single;

        return $this;
    }

    /**
     * Checks if the filter is treated as single.
     *
     * @return boolean
     */
    public function isSingle()
    {
        return $this->single;
    }

    /**
     * Sets type for the filter treated as single.
     *
     * @param string $singleType AbstractRangeOrSingleFilter::SINGLE_TYPE_*
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setSingleType($singleType)
    {
        $allowedTypes = [
            self::SINGLE_TYPE_EXACT,
            self::SINGLE_TYPE_GREATER,
            self::SINGLE_TYPE_GREATER_OR_EQUAL,
            self::SINGLE_TYPE_LESS,
            self::SINGLE_TYPE_LESS_OR_EQUAL,
        ];

        if (!is_string($singleType) || !in_array($singleType, $allowedTypes, true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid "single type" argument. Allowed types: [%s].', implode(', ', $allowedTypes)
            ));
        }

        $this->singleType = $singleType;

        return $this;
    }

    /**
     * Gets type for the filter treated as single.
     *
     * @return string
     */
    public function getSingleType()
    {
        return $this->singleType;
    }

    /**
     * Sets "from" value property name, which contains the "from" value.
     *
     * @param string $fromValuePropertyName
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setFromValuePropertyName($fromValuePropertyName)
    {
        if (!is_string($fromValuePropertyName) || empty($fromValuePropertyName)) {
            throw new InvalidArgumentException('"From value property name" argument must be string and must not be empty.');
        }

        if (!property_exists($this, $fromValuePropertyName)) {
            throw new InvalidArgumentException(sprintf('Property "%s" does not exist.', $fromValuePropertyName));
        }

        $this->fromValuePropertyName = $fromValuePropertyName;

        return $this;
    }

    /**
     * Gets "from" value property name, which contains the "from" value.
     *
     * @return string
     */
    public function getFromValuePropertyName()
    {
        return $this->fromValuePropertyName;
    }

    /**
     * Sets "to" value property name, which contains the "to" value.
     *
     * @param string $toValuePropertyName
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setToValuePropertyName($toValuePropertyName)
    {
        if (!is_string($toValuePropertyName) || empty($toValuePropertyName)) {
            throw new InvalidArgumentException('"To value property name" argument must be string and must not be empty.');
        }

        if (!property_exists($this, $toValuePropertyName)) {
            throw new InvalidArgumentException(sprintf('Property "%s" does not exist.', $toValuePropertyName));
        }

        $this->toValuePropertyName = $toValuePropertyName;

        return $this;
    }

    /**
     * Gets "to" value property name, which contains the "to" value.
     *
     * @return string
     */
    public function getToValuePropertyName()
    {
        return $this->toValuePropertyName;
    }

    /**
     * Sets "from" name postfix.
     *
     * @param string $fromPostfix
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setFromPostfix($fromPostfix)
    {
        if (!is_string($fromPostfix) || empty($fromPostfix)) {
            throw new InvalidArgumentException('"From postfix" argument must be string and must not be empty.');
        }

        $this->fromPostfix = $fromPostfix;

        return $this;
    }

    /**
     * Gets "from" name postfix.
     *
     * @return string
     */
    public function getFromPostfix()
    {
        return $this->fromPostfix;
    }

    /**
     * Sets "to" name postfix.
     *
     * @param string $toPostfix
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setToPostfix($toPostfix)
    {
        if (!is_string($toPostfix) || empty($toPostfix)) {
            throw new InvalidArgumentException('"To postfix" argument must be string and must not be empty.');
        }

        $this->toPostfix = $toPostfix;

        return $this;
    }

    /**
     * Gets "to" name postfix.
     *
     * @return string
     */
    public function getToPostfix()
    {
        return $this->toPostfix;
    }

    /**
     * Gets "from" type for the filter treated as ranged.
     *
     * @return string
     */
    public function getRangedFromType()
    {
        return $this->rangedFromType;
    }

    /**
     * Sets "from" type for the filter treated as ranged.
     *
     * @param string $rangedFromType AbstractRangeOrSingleFilter::RANGED_FROM_TYPE_*
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setRangedFromType($rangedFromType)
    {
        $allowedTypes = [self::RANGED_FROM_TYPE_GREATER, self::RANGED_FROM_TYPE_GREATER_OR_EQUAL];

        if (!is_string($rangedFromType) || !in_array($rangedFromType, $allowedTypes, true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid "ranged to type" argument. Allowed types: [%s].', implode(', ', $allowedTypes)
            ));
        }

        $this->rangedFromType = $rangedFromType;

        return $this;
    }

    /**
     * Gets "to" type for the filter treated as ranged.
     *
     * @return string
     */
    public function getRangedToType()
    {
        return $this->rangedToType;
    }

    /**
     * Sets "to" type for the filter treated as ranged.
     *
     * @param string $rangedToType AbstractRangeOrSingleFilter::RANGED_TO_TYPE_*
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setRangedToType($rangedToType)
    {
        $allowedTypes = [self::RANGED_TO_TYPE_LESS, self::RANGED_TO_TYPE_LESS_OR_EQUAL];

        if (!is_string($rangedToType) || !in_array($rangedToType, $allowedTypes)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid "ranged to type" argument. Allowed types: [%s].', implode(', ', $allowedTypes)
            ));
        }

        $this->rangedToType = $rangedToType;

        return $this;
    }

    /**
     * Gets form field type for "from" field.
     *
     * @return string
     */
    public function getFormFieldTypeRangedFrom()
    {
        return $this->formFieldTypeRangedFrom;
    }

    /**
     * Sets form field type for "from" field.
     *
     * @param string $formFieldTypeRangedFrom
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setFormFieldTypeRangedFrom($formFieldTypeRangedFrom)
    {
        if (!is_string($formFieldTypeRangedFrom) || empty($formFieldTypeRangedFrom)) {
            throw new InvalidArgumentException('"Form field type ranged from" argument must be string and must not be empty.');
        }

        $this->formFieldTypeRangedFrom = $formFieldTypeRangedFrom;

        return $this;
    }

    /**
     * Gets form field type for "to" field.
     *
     * @return string
     */
    public function getFormFieldTypeRangedTo()
    {
        return $this->formFieldTypeRangedTo;
    }

    /**
     * Sets form field type for "to" field.
     *
     * @param string $formFieldTypeRangedTo
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setFormFieldTypeRangedTo($formFieldTypeRangedTo)
    {
        if (!is_string($formFieldTypeRangedTo) || empty($formFieldTypeRangedTo)) {
            throw new InvalidArgumentException('"Form field type ranged to" argument must be string and must not be empty.');
        }

        $this->formFieldTypeRangedTo = $formFieldTypeRangedTo;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return CallableFunctionValidatorInterface|ConvertRangedValueFunctionValidator
     */
    public function getCallableValidatorConvertValue()
    {
        if ($this->callableValidatorConvertValue === false) {
            $this->callableValidatorConvertValue = new ConvertRangedValueFunctionValidator();
        }

        return $this->callableValidatorConvertValue;
    }
}
