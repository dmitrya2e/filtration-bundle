<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Filter\Sphinx\API;

use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidHandlerException;
use Da2e\FiltrationBundle\Model\FilterHandlerModel;

/**
 * Trait with helpful methods for Sphinx API filters.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
trait SphinxTypeTrait
{
    /**
     * @see \Da2e\FiltrationBundle\Filter\Filter\FilterInterface::getType()
     *
     * @return string
     */
    public function getType()
    {
        return FilterHandlerModel::HANDLER_SPHINX_API;
    }

    /**
     * Checks SphinxClient handler instance.
     *
     * @param mixed|object|\SphinxClient $handler
     *
     * @throws InvalidHandlerException On invalid handler type
     */
    protected function checkSphinxHandlerInstance($handler)
    {
        if (!($handler instanceof \SphinxClient)) {
            throw new InvalidHandlerException(sprintf(
                'Handler "%s" is not an instance of SphinxClient object.',
                is_object($handler) ? get_class($handler) : gettype($handler)
            ));
        }
    }
}
