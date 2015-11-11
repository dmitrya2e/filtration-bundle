<?php

namespace Da2e\FiltrationBundle\Filter\Filter;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * An interface, which must be used by filter to indicate, if the filter has a form representation.
 *
 * @package Da2e\FiltrationBundle\Filter\Filter
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
