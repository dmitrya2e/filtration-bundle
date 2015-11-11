<?php

namespace Da2e\FiltrationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * The default form type for a filter representation.
 * This class is created just for convenience of creation forms for the filters.
 * Though it is not necessary to use this class or any other custom class.
 * A form created via FormBuilder at a runtime is absolutely fine.
 *
 * @package Da2e\FiltrationBundle\Form\Type
 */
class FilterType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Da2e\FiltrationBundle\Filter\FilterInterface',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'da2e_filter_form_type';
    }
}
