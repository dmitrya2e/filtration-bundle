<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\FilterOption;

use Da2e\FiltrationBundle\Filter\Filter\FilterOptionInterface;

/**
 * An interface, which must be used by filter option handler class.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
