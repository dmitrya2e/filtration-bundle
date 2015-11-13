<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Filter;

use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidHandlerException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class InvalidHandlerExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\Filter
 */
class InvalidHandlerExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new InvalidHandlerException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException', $e);
    }
}
