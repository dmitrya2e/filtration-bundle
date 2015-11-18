<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Executor;

use Da2e\FiltrationBundle\Filter\Collection\CollectionInterface;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * An interface, which must be used by filter executor class.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
interface FilterExecutorInterface
{
    /**
     * Executes each filter from the collection if the filter is applied.
     *
     * @param CollectionInterface|FilterInterface[] $collection The filter collection
     * @param array                                 $handlers   Filtration handlers
     *
     * @return mixed
     */
    public function execute(CollectionInterface $collection, array $handlers);
}
