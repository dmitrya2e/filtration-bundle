<?php

namespace Da2e\FiltrationBundle\Filter\Filter\Sphinx\API;

use Da2e\FiltrationBundle\Filter\Filter\AbstractChoiceFilter;
use Da2e\FiltrationBundle\Filter\Filter\Sphinx\SphinxFilterTrait;
use \SphinxClient as SphinxClient;

/**
 * Class ChoiceFilter
 * @package Da2e\FiltrationBundle\Filter\Filter\Filter\Sphinx\API
 */
class ChoiceFilter extends AbstractChoiceFilter
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
