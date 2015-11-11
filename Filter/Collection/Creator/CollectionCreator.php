<?php

namespace Da2e\FiltrationBundle\Filter\Collection\Creator;

use Da2e\FiltrationBundle\Exception\Filter\Collection\Creator\CollectionCreatorException;

/**
 * The default filter collection creator.
 *
 * @package Da2e\FiltrationBundle\Filter\Collection\Creator
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
