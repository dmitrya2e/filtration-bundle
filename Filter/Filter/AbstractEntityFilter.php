<?php

namespace Da2e\FiltrationBundle\Filter\Filter;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AbstractEntityFilter
 * @package Da2e\FiltrationBundle\Filter\Filter
 *
 * @abstract
 */
abstract class AbstractEntityFilter extends AbstractFilter
{
    /**
     * @var string
     */
    protected $formFieldType = 'entity';

    /**
     * {@inheritDoc}
     */
    public function appendFormFieldsToForm(FormBuilderInterface $formBuilder)
    {
        $defaultOptions = array_merge([
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'label'    => $this->getTitle(),
            'data'     => $this->getDefaultValue(),
        ], $this->getFormOptions());

        $defaultOptions['property_path'] = 'value';

        $formBuilder->add($this->getValuePropertyName(), $this->getFormFieldType(), $defaultOptions);

        return $this;
    }

    /**
     * @return array
     */
    protected function convertValue()
    {
        $values = $this->getValue();
        $convertedValues = array();

        if (($values instanceof Collection) || is_array($values)) {
            foreach ($values as $value) {
                if (!is_object($value) || !method_exists($value, 'getId')) {
                    continue;
                }

                $convertedValues[] = $value->getId();
            }
        } elseif (is_object($values) && method_exists($values, 'getId')) {
            $convertedValues[] = $values->getId();
        }

        return $convertedValues;
    }
}