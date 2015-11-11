<?php

namespace Da2e\FiltrationBundle\Filter\Creator;

use Da2e\FiltrationBundle\Exception\Filter\Creator\FilterCreatorException;
use Da2e\FiltrationBundle\Filter\Chain\FilterChainInterface;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * The default filter creator class.
 * Used for creating filter objects.
 *
 * @package Da2e\FiltrationBundle\Manager
 */
class FilterCreator implements FilterCreatorInterface
{
    /**
     * @var FilterChainInterface
     */
    protected $filterChain;

    /**
     * @param FilterChainInterface $filterChain
     */
    public function __construct(FilterChainInterface $filterChain)
    {
        $this->filterChain = $filterChain;
    }

    /**
     * {@inheritDoc}
     *
     * @return FilterInterface
     * @throws FilterCreatorException If there is no such filter type alias
     */
    public function create($filterTypeAlias)
    {
        $alias = strtolower($filterTypeAlias);

        if (!$this->filterChain->hasType($alias)) {
            throw new FilterCreatorException(sprintf('Given filter type alias "%s" does not exist.', $alias));
        }

        /** @var FilterInterface $item */
        $item = clone $this->filterChain->getType($alias);

        // Set a default unique name (normally, it must be set explicitly).
        $item->setName($this->generateUniqueName($alias));

        return $item;
    }

    /**
     * Generates default unique name for filter.
     *
     * @param string $alias
     *
     * @return string
     */
    protected function generateUniqueName($alias)
    {
        return sprintf('%s_%s', $alias, uniqid(rand()));
    }
}
