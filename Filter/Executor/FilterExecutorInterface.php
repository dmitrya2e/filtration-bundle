<?php

namespace Da2e\FiltrationBundle\Filter\Executor;

use Da2e\FiltrationBundle\Filter\Collection\CollectionInterface;

/**
 * An interface, which must be used by filter executor class.
 *
 * @package Da2e\FiltrationBundle\Filter\Executor
 */
interface FilterExecutorInterface
{
    /**
     * Executes each filter from the collection if the filter is applied.
     *
     * @param CollectionInterface $collection The filter collection
     * @param array               $handlers   Filtration handlers
     *
     * @return mixed
     */
    public function execute(CollectionInterface $collection, array $handlers);
}
