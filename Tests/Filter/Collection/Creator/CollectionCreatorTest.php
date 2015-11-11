<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Collection\Creator;

use Da2e\FiltrationBundle\Filter\Collection\Creator\CollectionCreator;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class CollectionCreatorTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Collection\Creator
 */
class CollectionCreatorTest extends TestCase
{
    public function testConstruct()
    {
        $creator = new CollectionCreator('\stdClass');
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Collection\Creator\CollectionCreatorException
     */
    public function testConstruct_InvalidClass()
    {
        $creator = new CollectionCreator('\stdClass123');
    }

    public function testCreate()
    {
        $creator = new CollectionCreator('\stdClass');
        $result = $creator->create();
        $this->assertInstanceOf('\stdClass', $result);
    }
}
