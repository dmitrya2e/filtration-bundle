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

use Da2e\FiltrationBundle\Filter\Filter\AbstractEntityFilter;

/**
 * Class EntityFilter
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class EntityFilter extends AbstractEntityFilter
{
    use DoctrineORMFilterTrait;

    /**
     * {@inheritDoc}
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     */
    public function applyFilter($queryBuilder)
    {
        $this->checkDoctrineORMHandlerInstance($queryBuilder);

        if ($this->hasAppliedValue() === false) {
            return $this;
        }

        $queryBuilder
            ->andWhere(sprintf('%s IN (:%s)', $this->getFieldName(), $this->getName()))
            ->setParameter($this->getName(), $this->getConvertedValue());

        return $this;
    }
}
