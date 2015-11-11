<?php

namespace Da2e\FiltrationBundle\Filter\Collection\Creator;

use Da2e\FiltrationBundle\Filter\Collection\CollectionInterface;

/**
 * An interfaces used in filter collection creator class.
 *
 * @package Da2e\FiltrationBundle\Filter\Collection\Creator
 */
interface CollectionCreatorInterface
{
    /**
     * Creates a filter collection.
     *
     * @return mixed|CollectionInterface
     */
    public function create();
}
