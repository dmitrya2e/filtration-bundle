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
 * An interface, which must be used by filter to indicate, if the filter has a form representation.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
interface FilterHasFormInterface extends FilterInterface
{
    /**
     * Checks if the filter has a form representation.
     * If the filter has not a form representation, it will be omitted by the form creator class.
     *
     * @return bool
     */
    public function hasForm();

    /**
     * Appends corresponding filter form fields to the form builder.
     *
     * @param FormBuilderInterface $formBuilder
     *
     * @return mixed
     */
    public function appendFormFieldsToForm(FormBuilderInterface $formBuilder);
}
