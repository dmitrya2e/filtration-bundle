<?php

namespace Da2e\FiltrationBundle\Filter\Collection;

/**
 * An interface, which must be used in filter collection manager class.
 *
 * @package Da2e\FiltrationBundle\Filter\Collection
 */
interface CollectionManagerInterface
{
    /**
     * Adds a filter to the collection.
     *
     * @param string              $filterTypeAlias  The defined filter service alias
     * @param string              $name             The internal name of the filter
     * @param CollectionInterface $filterCollection The filter collection
     * @param array               $options          (Optional) The filter options
     *
     * @return mixed
     */
    public function add($filterTypeAlias, $name, CollectionInterface $filterCollection, array $options = []);
}
