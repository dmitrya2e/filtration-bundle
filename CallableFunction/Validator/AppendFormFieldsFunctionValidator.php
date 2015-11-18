<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

/**
 * Filters "append form fields" callable function validator.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class AppendFormFieldsFunctionValidator extends BaseFunctionValidator implements CallableFunctionValidatorInterface
{
    /**
     * {@inheritDoc}
     */
    protected $argumentTypes = [
        ['type' => 'Da2e\FiltrationBundle\Filter\Filter\FilterInterface'],
        ['type' => 'Symfony\Component\Form\FormBuilderInterface'],
    ];
}
