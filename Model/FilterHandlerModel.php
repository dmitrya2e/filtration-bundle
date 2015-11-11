<?php

namespace Da2e\FiltrationBundle\Model;

/**
 * The class containing some static info/config about the filter handlers supported by the bundle.
 *
 * @package Da2e\FiltrationBundle\Model
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
