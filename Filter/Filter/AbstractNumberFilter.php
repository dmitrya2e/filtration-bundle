<?php

namespace Da2e\FiltrationBundle\Filter\Filter;

use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AbstractNumberFilter
 * @package Da2e\FiltrationBundle\Filter\Filter
 *
 * @abstract
 */
abstract class AbstractNumberFilter extends AbstractRangeOrSingleFilter
{
    /**
     * @var string
     */
    protected $formFieldType = 'number';

    /**
     * Form field type name for the "from" field for the ranged mode.
     *
     * @var string
     */
    protected $formFieldTypeRangedFrom = 'number';

    /**
     * Form field type name for the "to" field for the ranged mode.
     *
     * @var string
     */
    protected $formFieldTypeRangedTo = 'number';

    /**
     * Treat values like floats or integers.
     *
     * @var bool
     */
    protected $float = false;

    /**
     * {@inheritDoc}
     */
    public static function getValidOptions()
    {
        return array_merge(parent::getValidOptions(), [
            'float' => [
                'setter' => 'setFloat',
                'empty'  => false,
                'type'   => 'bool',
            ],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function appendFormFieldsToForm(FormBuilderInterface $formBuilder)
    {
        if ($this->isSingle()) {
            return $this->appendSingleFormFields($formBuilder);
        }

        return $this->appendRangedFormFields($formBuilder);
    }

    /**
     * @return boolean
     */
    public function isFloat()
    {
        return $this->float;
    }

    /**
     * Sets Float.
     *
     * @param boolean $float
     *
     * @return static
     * @throws InvalidArgumentException On invalid argument
     */
    public function setFloat($float)
    {
        if (!is_bool($float)) {
            throw new InvalidArgumentException('Float argument must be boolean.');
        }

        $this->float = $float;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return float|int|null
     */
    protected function convertSingleValue()
    {
        return $this->getNumberValueOrNull($this->getValue());
    }

    /**
     * {@inheritDoc}
     *
     * @return float[]|int[]|null[]|array [ fromValue, toValue ]
     */
    protected function convertRangedValue()
    {
        return [
            $this->getNumberValueOrNull($this->getFromValue()),
            $this->getNumberValueOrNull($this->getToValue()),
        ];
    }

    /**
     * Appends form fields for "exact" mode.
     *
     * @param FormBuilderInterface $formBuilder
     *
     * @return $this
     */
    protected function appendSingleFormFields(FormBuilderInterface $formBuilder)
    {
        $defaultOptions = [
            'label'    => $this->getTitle(),
            'required' => false,
        ];

        $userOptions = array_key_exists('single', $this->getFormOptions())
            ? $this->getFormOptions()['single']
            : [];

        if (count($userOptions) > 0) {
            $defaultOptions = array_merge($defaultOptions, $userOptions);
        }

        $defaultOptions['property_path'] = 'value';

        $formBuilder->add($this->getValuePropertyName(), $this->getFormFieldType(), $defaultOptions);

        return $this;
    }

    /**
     * Appends form fields for "range" mode.
     *
     * @param FormBuilderInterface $formBuilder
     *
     * @return $this
     */
    protected function appendRangedFormFields(FormBuilderInterface $formBuilder)
    {
        // "From" options
        $fromDefaultOptions = [
            'label'    => 'da2e.filtration.number_filter.ranged.from.label',
            'required' => false,
        ];

        $fromUserOptions = array_key_exists('ranged_from', $this->getFormOptions())
            ? $this->getFormOptions()['ranged_from']
            : [];

        if (count($fromUserOptions) > 0) {
            $fromDefaultOptions = array_merge($fromDefaultOptions, $fromUserOptions);
        }

        $fromDefaultOptions['property_path'] = 'fromValue';

        // "To" options
        $toDefaultOptions = [
            'label'    => 'da2e.filtration.number_filter.ranged.to.label',
            'required' => false,
        ];

        $toUserOptions = array_key_exists('ranged_to', $this->getFormOptions())
            ? $this->getFormOptions()['ranged_to']
            : [];

        if (count($toUserOptions) > 0) {
            $toDefaultOptions = array_merge($toDefaultOptions, $toUserOptions);
        }

        $toDefaultOptions['property_path'] = 'toValue';

        $formBuilder->add($this->getFromValuePropertyName(), $this->getFormFieldTypeRangedFrom(), $fromDefaultOptions);
        $formBuilder->add($this->getToValuePropertyName(), $this->getFormFieldTypeRangedTo(), $toDefaultOptions);

        return $this;
    }

    /**
     * Gets correct number value (float/int) or null if the filter was not applied.
     *
     * @param float|int|string|null $value
     *
     * @return float|int|null
     */
    private function getNumberValueOrNull($value)
    {
        if (!is_scalar($value) || is_bool($value) || (string) $value === '' || !is_numeric((string) $value)) {
            return null;
        }

        if ($this->isFloat() === true) {
            return is_float($value) ? $value : (float) $value;
        }

        return is_int($value) ? $value : (int) $value;
    }
}
