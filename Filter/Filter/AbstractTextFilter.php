<?php

namespace Da2e\FiltrationBundle\Filter\Filter;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * A base abstract text filter class.
 *
 * @package Da2e\FiltrationBundle\Filter\Filter
 * @abstract
 */
abstract class AbstractTextFilter extends AbstractFilter
{
    /**
     * @var string
     */
    protected $formFieldType = 'text';

    /**
     * {@inheritDoc}
     */
    public function appendFormFieldsToForm(FormBuilderInterface $formBuilder)
    {
        $defaultOptions = array_merge([
            'required' => false,
            'label'    => $this->getTitle(),
            'data'     => $this->getDefaultValue(),
        ], $this->getFormOptions());

        $defaultOptions['property_path'] = 'value';

        $formBuilder->add($this->getValuePropertyName(), $this->getFormFieldType(), $defaultOptions);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function convertValue()
    {
        if (!is_scalar($this->getValue())) {
            return '';
        }

        return (string) $this->getValue();
    }
}
