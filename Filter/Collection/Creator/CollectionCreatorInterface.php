<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Collection\Creator;

use Da2e\FiltrationBundle\Filter\Collection\CollectionInterface;

/**
 * An interfaces used in filter collection creator class.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
