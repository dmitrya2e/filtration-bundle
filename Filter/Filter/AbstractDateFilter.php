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

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Base abstract date filter, which may be extended by all concrete date filters.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 * @abstract
 */
abstract class AbstractDateFilter extends AbstractRangeOrSingleFilter
{
    /**
     * @var string
     */
    protected $formFieldType = 'date';

    /**
     * Form field type name for the "from" field in the ranged mode.
     *
     * @var string
     */
    protected $formFieldTypeRangedFrom = 'date';

    /**
     * Form field type name for the "to" field in the ranged mode.
     *
     * @var string
     */
    protected $formFieldTypeRangedTo = 'date';

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
     * Applies single date form fields.
     *
     * @var FormBuilderInterface $formBuilder
     *
     * @return static
     */
    protected function appendSingleFormFields(FormBuilderInterface $formBuilder)
    {
        $defaultOptions = [
            'label'    => $this->getTitle(),
            'widget'   => 'single_text',
            'format'   => 'dd/MM/yyyy',
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
     * Appends ranged date form fields.
     *
     * @var FormBuilderInterface $formBuilder
     *
     * @return static
     */
    protected function appendRangedFormFields(FormBuilderInterface $formBuilder)
    {
        // "From" options
        $fromDefaultOptions = [
            'label'    => 'da2e.filtration.date_filter.ranged.from.label',
            'widget'   => 'single_text',
            'format'   => 'dd/MM/yyyy',
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
            'label'    => 'da2e.filtration.date_filter.ranged.to.label',
            'widget'   => 'single_text',
            'format'   => 'dd/MM/yyyy',
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
     * {@inheritDoc}
     *
     * @return \DateTime|null
     */
    protected function convertSingleValue()
    {
        return $this->getDateTimeObjectValueOrNull($this->getValue());
    }

    /**
     * {@inheritDoc}
     *
     * @return \DateTime[]||null[]|array [ fromValue, toValue ]
     */
    protected function convertRangedValue()
    {
        return [
            $this->getDateTimeObjectValueOrNull($this->getFromValue()),
            $this->getDateTimeObjectValueOrNull($this->getToValue()),
        ];
    }

    /**
     * Gets \DateTime object value if the filter was applied or null if the filter was not applied.
     *
     * @param \DateTime|null $value
     *
     * @return null|\DateTime DateTime object without time
     */
    private function getDateTimeObjectValueOrNull($value)
    {
        if ($value instanceof \DateTime) {
            $value->setTime(0, 0, 0);

            return $value;
        }

        return null;
    }
}
