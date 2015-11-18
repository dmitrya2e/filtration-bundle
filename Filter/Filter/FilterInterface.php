<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Filter;

/**
 * An interface, which must be used by all filter classes.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
interface FilterInterface
{
    /**
     * Sets the internal name of the filter (also used as the form name).
     *
     * @param string $name
     *
     * @return mixed
     */
    public function setName($name);

    /**
     * Gets the internal name of the filter.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the field name for the external data source of the filter.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function setFieldName($name);

    /**
     * Gets the filter handler type.
     *
     * @return string
     */
    public function getType();

    /**
     * Applies filtration of the filter (considering if the filter was actually applied).
     *
     * @param object|mixed $handler Any supported resource, which can handle filtration (e.g. QueryBuilder, ...)
     *
     * @return mixed
     */
    public function applyFilter($handler);
}
