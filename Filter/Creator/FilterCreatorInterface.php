<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Creator;

use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * An interface, which must be used by filter creator class.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
interface FilterCreatorInterface
{
    /**
     * Creates a filter with default parameters.
     *
     * @param string $filterTypeAlias The defined filter service alias
     * @param string $name            The name of the filter
     * @param array  $options         (Optional) The filter options
     *
     * @return mixed|FilterInterface
     */
    public function create($filterTypeAlias, $name = null, array $options = []);
}
