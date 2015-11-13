<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Collection\Creator;

use Da2e\FiltrationBundle\Exception\Filter\Collection\Creator\CollectionCreatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class CollectionCreatorExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\Collection\Creator
 */
class CollectionCreatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new CollectionCreatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\Collection\CollectionException', $e);
    }
}
