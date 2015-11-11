<?php

namespace Da2e\FiltrationBundle\Filter\Collection;

use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * An interface which must be used in filter collection class.
 *
 * @package Da2e\FiltrationBundle\Filter\Collection
 */
interface CollectionInterface
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
     * Gets all filters.
     *
     * @return mixed|FilterInterface[]
     */
    public function getFilters();

    /**
     * Checks if there are any filters.
     *
     * @return bool
     */
    public function hasFilters();
}
