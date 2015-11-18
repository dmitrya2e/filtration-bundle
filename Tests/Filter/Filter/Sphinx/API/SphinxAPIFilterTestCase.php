<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Filter\Filter\Sphinx\API;

use Da2e\FiltrationBundle\Tests\Filter\Filter\AbstractFilterTestCase;

/**
 * Class SphinxAPIFilterTestCase
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
