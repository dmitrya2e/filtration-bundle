<?php

namespace Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM;

use Da2e\FiltrationBundle\Filter\Filter\AbstractNumberFilter;
use Doctrine\ORM\QueryBuilder;

/**
 * Class NumberFilter
 * @package Da2e\FiltrationBundle\Filter\Filter\Doctrine\ORM
 */
class NumberFilter extends AbstractNumberFilter
{
    use DoctrineORMFilterTrait;
    use RangedOrSingleFilterTrait;

    /**
     * {@inheritDoc}
     *
     * @param QueryBuilder $queryBuilder
     */
    public function applyFilter($queryBuilder)
    {
        $this->checkDoctrineORMHandlerInstance($queryBuilder);

        if ($this->hasAppliedValue() === false) {
            return $this;
        }

        if ($this->isSingle() === true) {
            return $this->applySingleFilter($queryBuilder);
        }

        return $this->applyRangedFilter($queryBuilder);
    }

    /**
     * Applies single filter.
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return $this
     */
    protected function applySingleFilter(QueryBuilder $queryBuilder)
    {
        $queryBuilder
            ->andWhere(sprintf(
                '%s %s :%s',
                $this->getFieldName(),
                $this->getComparisonOperatorForSingleField($this->getSingleType()),
                $this->getName()
            ))
            ->setParameter($this->getName(), $this->getConvertedValue());

        return $this;
    }

    /**
     * Applies ranged filter.
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return $this
     */
    protected function applyRangedFilter(QueryBuilder $queryBuilder)
    {
        $fromValue = $this->getConvertedFromValue();
        $toValue = $this->getConvertedToValue();

        $fromComparisonOperator = $this->getComparisonOperatorForRangedField($this->getRangedFromType());
        $toComparisonOperator = $this->getComparisonOperatorForRangedField($this->getRangedToType());
        $fromValueParam = sprintf('%s_%s', $this->getName(), $this->getFromPostfix());
        $toValueParam = sprintf('%s_%s', $this->getName(), $this->getToPostfix());

        $queryBuilder
            ->andWhere(sprintf(
                '%s %s :%s', $this->getFieldName(), $fromComparisonOperator, $fromValueParam
            ))
            ->andWhere(sprintf(
                '%s %s :%s', $this->getFieldName(), $toComparisonOperator, $toValueParam
            ))
            ->setParameter($fromValueParam, $fromValue)
            ->setParameter($toValueParam, $toValue);

        return $this;
    }
}
