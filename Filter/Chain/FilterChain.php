<?php

namespace Da2e\FiltrationBundle\Filter\Chain;

use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * The default filter chain class,
 * which is used in bundles FiltrationCompilePass for registering a tagged filter services.
 *
 * @package Da2e\FiltrationBundle\Filter\Chain
 */
class FilterChain implements FilterChainInterface
{
    /**
     * The filter types.
     *
     * @var array
     */
    protected $types = [];

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function addType(FilterInterface $type, $alias)
    {
        $this->types[$alias] = $type;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return FilterInterface|false
     */
    public function getType($alias)
    {
        if (array_key_exists($alias, $this->types)) {
            return $this->types[$alias];
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function hasType($alias)
    {
        return array_key_exists($alias, $this->types) === true;
    }
}
