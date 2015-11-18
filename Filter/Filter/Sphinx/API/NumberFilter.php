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

use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException;
use Da2e\FiltrationBundle\Exception\Filter\Filter\LogicException;
use Da2e\FiltrationBundle\Filter\Filter\AbstractNumberFilter;
use Da2e\FiltrationBundle\Filter\Filter\Sphinx\SphinxFilterTrait;
use \SphinxClient as SphinxClient;

/**
 * Class NumberFilter
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class NumberFilter extends AbstractNumberFilter
{
    use SphinxFilterTrait;
    use SphinxTypeTrait;

    /**
     * NumberFilter has settings related to SphinxSearch API and \SphinxClient library only. These are:
     *  - default min value
     *  - default max value
     *
     * This is done, because \SphinxClient does not allow to make filter
     * like "setFilterRange('field', null, 100)" or "setFilterRange('field', 15, null)".
     *
     * In other words it can't filter by min value OR by max value only, it require both bounds to be set.
     *
     * So basically, when no min value is applied, the default min value is considered as actual min value.
     * And when no max value is applied, the default max value is considered as actual max value.
     */

    /**
     * @var mixed|int
     */
    protected $defaultMin = 0;

    /**
     * @var mixed|int
     */
    protected $defaultMax = PHP_INT_MAX;

    /**
     * @var float
     */
    protected $defaultFloatStep = 0.01;

    /**
     * {@inheritDoc}
     */
    public static function getValidOptions()
    {
        return array_merge(parent::getValidOptions(), [
            'exclude'            => self::getExcludeOptionDescription(),
            'default_min'        => [
                'setter' => 'setDefaultMin',
                'empty'  => false,
                'type'   => ['int', 'float'],
            ],
            'default_max'        => [
                'setter' => 'setDefaultMax',
                'empty'  => false,
                'type'   => ['int', 'float'],
            ],
            'default_float_step' => [
                'setter' => 'setDefaultFloatStep',
                'empty'  => false,
                'type'   => 'float',
            ],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function applyFilter($sphinxClient)
    {
        $this->checkSphinxHandlerInstance($sphinxClient);

        if ($this->hasAppliedValue() === false) {
            return $this;
        }

        if ($this->isSingle()) {
            return $this->applySingleFilter($sphinxClient);
        }

        return $this->applyRangedFilter($sphinxClient);
    }

    /**
     * @param int|float $defaultMin
     *
     * @return static
     * @throws InvalidArgumentException On invalid parameter
     */
    public function setDefaultMin($defaultMin)
    {
        if (!is_float($defaultMin) && !is_int($defaultMin)) {
            throw new InvalidArgumentException('"Default min" argument must be either float or int.');
        }

        $this->defaultMin = $defaultMin;

        return $this;
    }

    /**
     * @return int|float
     */
    public function getDefaultMin()
    {
        return $this->defaultMin;
    }

    /**
     * @param int|float $defaultMax
     *
     * @return static
     * @throws InvalidArgumentException On invalid parameter
     */
    public function setDefaultMax($defaultMax)
    {
        if (!is_float($defaultMax) && !is_int($defaultMax)) {
            throw new InvalidArgumentException('"Default max" argument must be either float or int.');
        }

        $this->defaultMax = $defaultMax;

        return $this;
    }

    /**
     * @return int|float
     */
    public function getDefaultMax()
    {
        return $this->defaultMax;
    }

    /**
     * @param float $defaultFloatStep
     *
     * @return NumberFilter
     * @throws InvalidArgumentException On invalid parameter
     */
    public function setDefaultFloatStep($defaultFloatStep)
    {
        if (!is_float($defaultFloatStep) || $defaultFloatStep < 0.0 || $defaultFloatStep === 0.0) {
            throw new InvalidArgumentException('"Default float step" argument must be either float and greater than 0.');
        }

        $this->defaultFloatStep = $defaultFloatStep;

        return $this;
    }

    /**
     * @return float
     */
    public function getDefaultFloatStep()
    {
        return $this->defaultFloatStep;
    }

    /**
     * Applies exact filter.
     *
     * @param \SphinxClient $sphinxClient
     *
     * @return $this
     * @throws LogicException On invalid value bounding
     */
    protected function applySingleFilter(\SphinxClient $sphinxClient)
    {
        $value = $this->getConvertedValue();

        if ($this->getSingleType() === self::SINGLE_TYPE_EXACT) {
            $sphinxClient->SetFilter($this->getFieldName(), $value, $this->isExclude());
        } else {
            if (in_array($this->getSingleType(), [self::SINGLE_TYPE_GREATER, self::SINGLE_TYPE_GREATER_OR_EQUAL])) {
                if ($value > $this->getConvertedDefaultMax()) {
                    throw new LogicException(sprintf(
                        'Single value can not be greater, than %s.', $this->getConvertedDefaultMax()
                    ));
                }
            } else {
                if ($value < $this->getConvertedDefaultMin()) {
                    throw new LogicException(sprintf(
                        'Single value can not be less, than %s.', $this->getConvertedDefaultMin()
                    ));
                }
            }

            $methodName = $this->isFloat() ? 'SetFilterFloatRange' : 'SetFilterRange';
            $bounds = $this->getBoundsForSingleNonExactFilter();
            $sphinxClient->$methodName($this->getFieldName(), $bounds[0], $bounds[1], $this->isExclude());
        }

        return $this;
    }

    /**
     * Applies ranged filter.
     *
     * @param \SphinxClient $sphinxClient
     *
     * @return $this
     * @throws LogicException On unexpected errors
     */
    protected function applyRangedFilter(\SphinxClient $sphinxClient)
    {
        if ($this->getConvertedDefaultMin() > $this->getConvertedDefaultMax()) {
            throw new LogicException('Default min value must not be greater than default max value.');
        }

        $methodName = $this->isFloat() === true ? 'SetFilterFloatRange' : 'SetFilterRange';
        $bounds = $this->getBoundsForRangedFilter();
        $sphinxClient->$methodName($this->getFieldName(), $bounds[0], $bounds[1], $this->isExclude());

        return $this;
    }

    /**
     * Gets min/max bounds for single filter (applySingleFilter()).
     *
     * @return array|int[]|float[] [ min_value|float|int, max_value|float|int ]
     */
    protected function getBoundsForSingleNonExactFilter()
    {
        $value = $this->getConvertedValue();
        $minValue = $this->getConvertedDefaultMin();
        $maxValue = $this->getConvertedDefaultMax();

        switch ($this->getSingleType()) {
            case self::SINGLE_TYPE_GREATER:
                if ($value < $maxValue) {
                    $value += $this->getValueMinStep();
                }

                $minValue = $value;
                break;

            case self::SINGLE_TYPE_GREATER_OR_EQUAL:
                $minValue = $value;
                break;

            case self::SINGLE_TYPE_LESS:
                if ($value > $minValue) {
                    $value -= $this->getValueMinStep();
                }

                $maxValue = $value;
                break;

            case self::SINGLE_TYPE_LESS_OR_EQUAL:
                $maxValue = $value;
                break;
        }

        return [$minValue, $maxValue];
    }

    /**
     * Gets min/max bounds for ranged filter (applyRangedFilter()).
     *
     * @return array|int[]|float[] [ min_value|float|int, max_value|float|int ]
     */
    protected function getBoundsForRangedFilter()
    {
        $minValue = $this->getConvertedDefaultMin();
        $maxValue = $this->getConvertedDefaultMax();
        $fromValue = $this->getConvertedFromValue();
        $toValue = $this->getConvertedToValue();
        $hasMin = $fromValue !== null;
        $hasMax = $toValue !== null;

        if ($hasMin && $hasMax && ($toValue >= $fromValue)) {
            $minValue = $fromValue;
            $maxValue = $toValue;
        } elseif (!$hasMin && $hasMax) {
            $maxValue = $toValue;
        } elseif ($hasMin && !$hasMax) {
            $minValue = $fromValue;
        }

        if ($minValue !== $maxValue) {
            if ($this->getRangedFromType() === self::RANGED_FROM_TYPE_GREATER && $minValue < $maxValue) {
                $minValue += $this->getValueMinStep();
            }

            if ($this->getRangedToType() === self::RANGED_TO_TYPE_LESS && $maxValue > $minValue) {
                $maxValue -= $this->getValueMinStep();
            }
        }

        return [$minValue, $maxValue];
    }

    /**
     * Gets converted default min value.
     *
     * @return float|int
     */
    protected function getConvertedDefaultMin()
    {
        $min = $this->getDefaultMin();

        if ($this->isFloat()) {
            if (!is_float($min)) {
                return (float) $min;
            }

            return $min;
        }

        if (!is_int($min)) {
            return (int) $min;
        }

        return $min;
    }

    /**
     * Gets converted default max value.
     *
     * @return float|int
     */
    protected function getConvertedDefaultMax()
    {
        $max = $this->getDefaultMax();

        if ($this->isFloat()) {
            if (!is_float($max)) {
                return (float) $max;
            }

            return $max;
        }

        if (!is_int($max)) {
            return (int) $max;
        }

        return $max;
    }

    /**
     * Gets value minimal step.
     *
     * @return float|int
     */
    protected function getValueMinStep()
    {
        return $this->isFloat() === true ? $this->getDefaultFloatStep() : 1;
    }
}
