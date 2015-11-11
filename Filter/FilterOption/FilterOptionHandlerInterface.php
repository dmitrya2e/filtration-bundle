<?php

namespace Da2e\FiltrationBundle\Filter\FilterOption;

use Da2e\FiltrationBundle\Filter\Filter\FilterOptionInterface;

/**
 * An interface, which must be used by filter option handler class.
 *
 * @package Da2e\FiltrationBundle\Filter\FilterOption
 */
interface FilterOptionHandlerInterface
{
    /**
     * Handles option setting for the filter.
     *
     * @param FilterOptionInterface $filter
     * @param array                 $options [ name|string => value|mixed, ... ]
     *
     * @see FilterOptionInterface::getValidOptions() for detailed description of options parameter
     *
     * @return mixed
     */
    public function handle(FilterOptionInterface $filter, array $options);
}
