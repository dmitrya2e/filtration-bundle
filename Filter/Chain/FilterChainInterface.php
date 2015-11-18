<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Chain;

use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * An interface which must be used by filter chain class.
 * The filter chain class is used in FiltrationCompilePass for registering a tagged filter services.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
interface FilterChainInterface
{
    /**
     * Adds a filter type with alias.
     *
     * @param FilterInterface $type
     * @param string          $alias
     *
     * @return mixed
     */
    public function addType(FilterInterface $type, $alias);

    /**
     * Gets the filter type by its alias.
     *
     * @param string $alias
     *
     * @return FilterInterface|false
     */
    public function getType($alias);

    /**
     * Checks if the filter chain has a type by its alias.
     *
     * @param string $alias
     *
     * @return bool
     */
    public function hasType($alias);
}
