<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Model;

/**
 * The class contains some static info/config about the filter handlers supported by the bundle.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterHandlerModel
{
    /**
     * Doctrine ORM query builder.
     *
     * @const
     */
    const HANDLER_DOCTRINE_ORM = 'doctrine_orm';

    /**
     * SphinxSearch API (\SphinxClient lib).
     *
     * @const
     */
    const HANDLER_SPHINX_API = 'sphinx_api';

    /**
     * Gets default handler types supported by bundle.
     *
     * @return array
     */
    public static function getDefaultHandlerTypes()
    {
        return [
            self::HANDLER_DOCTRINE_ORM => '\Doctrine\ORM\QueryBuilder',
            self::HANDLER_SPHINX_API   => '\SphinxClient',
        ];
    }
}
