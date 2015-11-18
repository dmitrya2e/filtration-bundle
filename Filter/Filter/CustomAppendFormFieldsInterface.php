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

/**
 * An interface, which can be used by filter to indicate if it has a custom function for appending form fields.
 * The interface must be used in coupe with FilterHasFormInterface.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
interface CustomAppendFormFieldsInterface
{
    /**
     * Gets a custom function (lambda) for appending form fields.
     * The function must have an input signature with 2 arguments:
     *  - filter object (instance of \Da2e\FiltrationBundle\Filter\Filter\FilterInterface)
     *  - form builder object (instance of \Symfony\Component\Form\FormBuilderInterface)
     *
     * @return callable|mixed
     */
    public function getAppendFormFieldsFunction();
}
