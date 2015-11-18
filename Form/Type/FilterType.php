<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * The default form type for a filter representation form.
 * This class is created just for convenience of creation forms for the filters.
 * Though it is not necessary to use this class or any other custom class.
 * A form created via FormBuilder at a runtime is absolutely fine.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
