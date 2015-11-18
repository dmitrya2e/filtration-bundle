<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Model;

use Da2e\FiltrationBundle\Model\FilterHandlerModel;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterHandlerModelTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterHandlerModelTest extends TestCase
{
    public function testGetDefaultHandlerTypes()
    {
        $result = FilterHandlerModel::getDefaultHandlerTypes();

        $this->assertSame([
            FilterHandlerModel::HANDLER_DOCTRINE_ORM => '\Doctrine\ORM\QueryBuilder',
            FilterHandlerModel::HANDLER_SPHINX_API   => '\SphinxClient',
        ], $result);
    }
}
