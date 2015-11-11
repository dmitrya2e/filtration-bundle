<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API;

use Da2e\FiltrationBundle\Tests\Filter\Filter\AbstractFilterTestCase;

/**
 * Class SphinxAPIFilterTestCase
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API
 */
class SphinxAPIFilterTestCase extends AbstractFilterTestCase
{
    /**
     * Gets SphinxClient mock.
     *
     * @param bool|array|null $methods
     * @param bool|array|null $constructorArgs
     *
     * @return \Doctrine\ORM\QueryBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSphinxClientMock(
        $methods = ['SetFilter', 'SetFilterFloatRange', 'SetFilterRange'],
        $constructorArgs = false
    ) {
        return $this->getCustomMock('\SphinxClient', $methods, $constructorArgs);
    }
}
