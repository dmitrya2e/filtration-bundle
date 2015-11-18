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

/**
 * An interface, which must be used in filter collection manager class.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
