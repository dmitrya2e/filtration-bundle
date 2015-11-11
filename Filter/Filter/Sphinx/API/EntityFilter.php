<?php

namespace Da2e\FiltrationBundle\Filter\Filter\Sphinx\API;

use Da2e\FiltrationBundle\Filter\Filter\AbstractEntityFilter;
use Da2e\FiltrationBundle\Filter\Filter\Sphinx\SphinxFilterTrait;
use \SphinxClient as SphinxClient;

/**
 * Class EntityFilter
 * @package Da2e\FiltrationBundle\Filter\Filter\Sphinx\API
 */
class EntityFilter extends AbstractEntityFilter
{
    use SphinxFilterTrait;
    use SphinxTypeTrait;

    /**
     * {@inheritDoc}
     */
    public static function getValidOptions()
    {
        return array_merge(parent::getValidOptions(), [
            'exclude' => self::getExcludeOptionDescription(),
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * @param \SphinxClient $sphinxClient
     */
    public function applyFilter($sphinxClient)
    {
        $this->checkSphinxHandlerInstance($sphinxClient);

        if ($this->hasAppliedValue()) {
            $sphinxClient->SetFilter($this->getFieldName(), $this->getConvertedValue(), $this->isExclude());
        }

        return $this;
    }
}
