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

use Da2e\FiltrationBundle\Filter\Filter\AbstractTextFilter;
use Da2e\FiltrationBundle\Filter\Filter\Sphinx\SphinxFilterTrait;
use \SphinxClient as SphinxClient;

/**
 * Class TextFilter
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class TextFilter extends AbstractTextFilter
{
    use SphinxFilterTrait;
    use SphinxTypeTrait;

    /**
     * {@inheritDoc}
     *
     * @param \SphinxClient $sphinxClient
     */
    public function applyFilter($sphinxClient)
    {
        // Unfortunately, \SphinxClient has no possibility to set query text,
        // so it is impossible to apply Sphinx API TextFilter.
        // Use hasAppliedValue() and getConvertedValue() methods outside of this class to filter by text manually.
        return $this;
    }
}
