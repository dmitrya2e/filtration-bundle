<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM;

use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidHandlerException;
use Da2e\FiltrationBundle\Model\FilterHandlerModel;

/**
 * Trait DoctrineORMFilterTrait
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
trait DoctrineORMFilterTrait
{
    /**
     * @see \Da2e\FiltrationBundle\Filter\Filter\FilterInterface::getType()
     *
     * @return string
     */
    public function getType()
    {
        return FilterHandlerModel::HANDLER_DOCTRINE_ORM;
    }

    /**
     * Checks Doctrine ORM query builder handler instance.
     *
     * @param mixed|object|\Doctrine\ORM\QueryBuilder $handler
     *
     * @throws InvalidHandlerException On invalid handler type
     */
    protected function checkDoctrineORMHandlerInstance($handler)
    {
        if (!($handler instanceof \Doctrine\ORM\QueryBuilder)) {
            throw new InvalidHandlerException(sprintf(
                'Handler "%s" is not an instance of Doctrine\ORM\QueryBuilder object.',
                is_object($handler) ? get_class($handler) : gettype($handler)
            ));
        }
    }
}
