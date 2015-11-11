<?php

namespace Da2e\FiltrationBundle\Filter\Filter;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * A base abstract choice filter class.
 *
 * @package Da2e\FiltrationBundle\Filter\Filter
 * @abstract
 */
abstract class AbstractChoiceFilter extends AbstractFilter
{
    /**
     * @var string
     */
    protected $formFieldType = 'choice';

    /**
     * {@inheritDoc}
     */
    public function appendFormFieldsToForm(FormBuilderInterface $formBuilder)
    {
        $defaultOptions = array_merge([
            'choices'  => [],
            'expanded' => true,
            'multiple' => true,
            'required' => false,
            'label'    => $this->getTitle(),
            'data'     => $this->getDefaultValue(),
        ], $this->getFormOptions());

        $defaultOptions['property_path'] = 'value';

        $formBuilder->add($this->getValuePropertyName(), $this->getFormFieldType(), $defaultOptions);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    protected function convertValue()
    {
        $values = array();

        if (is_array($this->getValue())) {
            $values = array_map('intval', $this->getValue());
        } elseif ($this->getValue() != null) {
            $values[] = (int) $this->getValue();
        }

        return $values;
    }
}
