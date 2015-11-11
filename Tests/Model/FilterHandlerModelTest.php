<?php

namespace Da2e\FiltrationBundle\Tests\Model;

use Da2e\FiltrationBundle\Model\FilterHandlerModel;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterHandlerModelTest
 * @package Da2e\FiltrationBundle\Tests\Model
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
