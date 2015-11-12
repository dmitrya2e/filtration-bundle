<?php

namespace Da2e\FiltrationBundle\Filter\Collection;

use Da2e\FiltrationBundle\Exception\Filter\Collection\CollectionException;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * The default filter collection class.
 *
 * @package Da2e\FiltrationBundle\Filter\Collection
 */
class Collection extends BaseCollection implements CollectionInterface
{
    /**
     * {@inheritDoc}
     *
     * @return static
     * @throws CollectionException If the collection already contains the filter
     */
    public function addFilter(FilterInterface $filter)
    {
        if ($this->containsFilter($filter)) {
            throw new CollectionException(sprintf(
                'Collection already contains key "%s".', $filter->getName()
            ));
        }

        $this->add($filter);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return FilterInterface|false False if the filter is not found in the collection
     */
    public function getFilterByName($name)
    {
        return $this->get($name);
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function removeFilterByName($name)
    {
        return $this->remove($name);
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function containsFilter($filter)
    {
        return $this->has($filter);
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function hasFilters()
    {
        return $this->count() > 0;
    }
}
