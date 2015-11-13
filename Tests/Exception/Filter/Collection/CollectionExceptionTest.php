<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Collection;

use Da2e\FiltrationBundle\Exception\Filter\Collection\CollectionException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class CollectionExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\Collection
 */
class CollectionExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new CollectionException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
