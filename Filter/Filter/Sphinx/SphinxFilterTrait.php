<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Filter\Filter\Sphinx;

/**
 * Class SphinxFilterTrait
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
trait SphinxFilterTrait
{
    /**
     * @var bool
     */
    protected $exclude = false;

    /**
     * @return boolean
     */
    public function isExclude()
    {
        return $this->exclude;
    }

    /**
     * Sets exclude flag.
     *
     * @param boolean $exclude
     *
     * @return static
     */
    public function setExclude($exclude)
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * @return array
     */
    protected static function getExcludeOptionDescription()
    {
        return [
            'setter' => 'setExclude',
            'empty'  => false,
            'type'   => 'bool',
        ];
    }
}
