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
 * A base abstract choice filter class.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
