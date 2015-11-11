<?php

namespace Da2e\FiltrationBundle\Filter\Collection;

use Da2e\FiltrationBundle\Exception\Filter\Collection\CollectionException;
use Da2e\FiltrationBundle\Filter\Creator\FilterCreatorInterface;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Filter\FilterOption\FilterOptionHandlerInterface;

/**
 * The default filter collection manager class.
 *
 * @package Da2e\FiltrationBundle\Filter\Collection
 */
class CollectionManager implements CollectionManagerInterface
{
    /**
     * @var FilterCreatorInterface
     */
    protected $filterCreator;

    /**
     * @var FilterOptionHandlerInterface
     */
    protected $filterOptionHandler;

    /**
     * @param FilterCreatorInterface       $filterCreator
     * @param FilterOptionHandlerInterface $filterOptionHandler
     */
    public function __construct(
        FilterCreatorInterface $filterCreator,
        FilterOptionHandlerInterface $filterOptionHandler
    ) {
        $this->filterCreator = $filterCreator;
        $this->filterOptionHandler = $filterOptionHandler;
    }

    /**
     * {@inheritDoc}
     *
     * @return FilterInterface
     * @throws CollectionException If the filter does not implement the FilterInterface
     */
    public function add($filterTypeAlias, $name, CollectionInterface $filterCollection, array $options = [])
    {
        // Create a filter via FilterCreator class.
        $filter = $this->filterCreator->create($filterTypeAlias);

        if (!($filter instanceof FilterInterface)) {
            // The filter must implement the bundles FilterInterface.
            throw new CollectionException(sprintf(
                'Filter "%s" must implement Da2e\FiltrationBundle\Filter\Filter\FilterInterface interface.',
                get_class($filter)
            ));
        }

        $filter->setName($name);

        // By default filters field name is equal to its name.
        // If it is required to set a different field name, please use the filter options,
        // or set it manually via setter method setFieldName().
        $filter->setFieldName($name);

        if (count($options) > 0) {
            // Handle filter options if there are such.
            $this->filterOptionHandler->handle($filter, $options);
        }

        // Finally, add the filter to the collection.
        $filterCollection->addFilter($filter);

        return $filter;
    }
}
