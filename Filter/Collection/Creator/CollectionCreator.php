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

use Da2e\FiltrationBundle\Exception\Filter\Collection\Creator\CollectionCreatorException;

/**
 * The default filter collection creator.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class CollectionCreator implements CollectionCreatorInterface
{
    /**
     * A collection class fully-qualified name.
     *
     * @var string
     */
    protected $collectionClassName = '';

    /**
     * @param string $collectionClassName Collection class FQN
     *
     * @throws CollectionCreatorException On nonexistent class
     */
    public function __construct($collectionClassName)
    {
        if (!class_exists($collectionClassName)) {
            throw new CollectionCreatorException(sprintf(
                'Collection class "%s" does not exist.', $collectionClassName
            ));
        }

        $this->collectionClassName = $collectionClassName;
    }

    /**
     * {@inheritDoc}
     */
    public function create()
    {
        return new $this->collectionClassName;
    }
}
