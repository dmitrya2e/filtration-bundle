<?php

namespace Da2e\FiltrationBundle\Filter\Filter;

/**
 * An interface, which can be used by filter to indicate if it has a custom function for values transforming.
 *
 * @package Da2e\FiltrationBundle\Filter\Filter
 */
interface CustomTransformValuesInterface
{
    /**
     * Gets a custom function (lambda) for values transforming.
     * The function must have an input signature with 1 argument:
     *  - filter object (instance of \Da2e\FiltrationBundle\Filter\Filter\FilterInterface)
     *
     * @return callable|mixed
     */
    public function getTransformValuesFunction();
}
