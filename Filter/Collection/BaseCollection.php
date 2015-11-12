<?php

namespace Da2e\FiltrationBundle\Filter\Collection;

use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * A base filter collection class with base methods.
 *
 * @package Da2e\FiltrationBundle\Filter\Collection
 */
class BaseCollection implements \IteratorAggregate, \Countable
{
    /**
     * The internal filter collection keeper.
     *
     * @var array|FilterInterface[]
     */
    protected $collection = [];

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Adds a filter to the collection.
     *
     * @param FilterInterface $filter
     *
     * @return static
     */
    protected function add(FilterInterface $filter)
    {
        $this->collection[$filter->getName()] = $filter;

        return $this;
    }

    /**
     * Checks if a filter exists in the collection.
     *
     * @param string|FilterInterface $filterOrName
     *
     * @return bool
     */
    protected function has($filterOrName)
    {
        return array_key_exists($this->getNameByFilter($filterOrName), $this->collection);
    }

    /**
     * Gets a filter by name.
     *
     * @param string $name
     *
     * @return FilterInterface|false False if the collection does not contain the filter
     */
    protected function get($name)
    {
        return $this->has($name) ? $this->collection[$name] : false;
    }

    /**
     * Removes a filter from the collection.
     *
     * @param string|FilterInterface $filterOrName
     *
     * @return bool True on success, false on failure (if the filter is not found in the collection)
     */
    protected function remove($filterOrName)
    {
        $name = $this->getNameByFilter($filterOrName);

        if ($this->has($name)) {
            unset($this->collection[$name]);

            return true;
        }

        return false;
    }

    /**
     * Gets a filter name from the filter object.
     *
     * @param string|FilterInterface $filterOrName
     *
     * @return string The filter name
     */
    private function getNameByFilter($filterOrName)
    {
        if ($filterOrName instanceof FilterInterface) {
            return $filterOrName->getName();
        }

        return $filterOrName;
    }
}
