<?php

namespace Da2e\FiltrationBundle\Filter\Filter\Sphinx;

/**
 * Class SphinxFilterTrait
 * @package Da2e\FiltrationBundle\Filter\Filter\Sphinx
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
