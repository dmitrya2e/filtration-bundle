<?php

namespace Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM;

use Da2e\FiltrationBundle\Filter\Filter\AbstractEntityFilter;

/**
 * Class EntityFilter
 * @package Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM
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
