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
 * Base abstract text filter class, which may be extended by all concrete text filters.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
     * Casts value to string. If the value is not scalar, an empty string will be returned.
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
