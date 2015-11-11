<?php

namespace Da2e\FiltrationBundle\Filter\Creator;

use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * An interface, which must be used by filter creator class.
 *
 * @package Da2e\FiltrationBundle\Filter\Creator
 */
interface FilterCreatorInterface
{
    /**
     * Creates a filter with default parameters.
     *
     * @param string $filterTypeAlias The defined filter service alias
     *
     * @return mixed|FilterInterface
     */
    public function create($filterTypeAlias);
}
