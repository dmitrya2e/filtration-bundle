<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Collection;

use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * An interface which must be used in filter collection class.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
interface CollectionInterface extends \IteratorAggregate
{
    /**
     * Adds a filter to the collection.
     *
     * @param FilterInterface $filter
     *
     * @return mixed
     */
    public function addFilter(FilterInterface $filter);

    /**
     * Gets specific filter by its name.
     *
     * @param string $name Filter name
     *
     * @return mixed|FilterInterface
     */
    public function getFilterByName($name);

    /**
     * Removes specific filter by its name.
     *
     * @param string $name Filter name
     *
     * @return mixed
     */
    public function removeFilterByName($name);

    /**
     * Checks if specific filter exists in the collection.
     *
     * @param string $name Filter name
     *
     * @return bool
     */
    public function containsFilter($name);

    /**
     * Checks if there are any filters.
     *
     * @return bool
     */
    public function hasFilters();
}
