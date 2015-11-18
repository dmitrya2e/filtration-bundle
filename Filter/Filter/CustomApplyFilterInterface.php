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
 * An interface, which can be used by filter to indicate if it has a custom function for applying filtration.
 * The interface should be used in coupe with FilterInterface.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
interface CustomApplyFilterInterface
{
    /**
     * Gets a custom function (lambda) for applying filtration.
     * The function must have an input signature with 2 arguments:
     *  - filter object (instance of \Da2e\FiltrationBundle\Filter\Filter\FilterInterface)
     *  - handler object (or mixed value) (any handler resource, which can handle filtration, e.g. QueryBuilder, ...)
     *
     * @return callable|mixed
     */
    public function getApplyFilterFunction();
}
