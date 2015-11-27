<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Creator;

use Da2e\FiltrationBundle\Exception\Filter\Creator\FilterCreatorException;
use Da2e\FiltrationBundle\Filter\Chain\FilterChainInterface;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Filter\FilterOption\FilterOptionHandlerInterface;

/**
 * The default filter creator class.
 * Used for creating filter objects.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterCreator implements FilterCreatorInterface
{
    /**
     * @var FilterChainInterface
     */
    protected $filterChain;

    /**
     * @var FilterOptionHandlerInterface
     */
    protected $filterOptionHandler;

    /**
     * @param FilterChainInterface         $filterChain
     * @param FilterOptionHandlerInterface $filterOptionHandler
     */
    public function __construct(FilterChainInterface $filterChain, FilterOptionHandlerInterface $filterOptionHandler)
    {
        $this->filterChain = $filterChain;
        $this->filterOptionHandler = $filterOptionHandler;
    }

    /**
     * {@inheritDoc}
     *
     * @return FilterInterface
     * @throws FilterCreatorException If there is no such filter type alias or filter does not implement FilterInterface
     */
    public function create($filterTypeAlias, $name = null, array $options = [])
    {
        $alias = strtolower($filterTypeAlias);

        if (!$this->filterChain->hasType($alias)) {
            throw new FilterCreatorException(sprintf('Given filter type alias "%s" does not exist.', $alias));
        }


        /** @var FilterInterface $filter */
        $filter = clone $this->filterChain->getType($alias);

        if (!($filter instanceof FilterInterface)) {
            // The filter must implement the bundles FilterInterface.
            throw new FilterCreatorException(sprintf(
                'Filter "%s" must implement Da2e\FiltrationBundle\Filter\Filter\FilterInterface interface.',
                get_class($filter)
            ));
        }

        $filter->setName($name === null ? $this->generateUniqueName($alias) : $name);

        if (count($options) > 0) {
            // Handle filter options if there are such.
            $this->filterOptionHandler->handle($filter, $options);
        }

        return $filter;
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
