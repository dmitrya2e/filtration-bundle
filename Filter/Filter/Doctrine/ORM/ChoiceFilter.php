<?php

namespace Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM;

use Da2e\FiltrationBundle\Filter\Filter\AbstractChoiceFilter;

/**
 * Class ChoiceFilter
 * @package Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM
 */
class ChoiceFilter extends AbstractChoiceFilter
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
