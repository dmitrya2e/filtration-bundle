<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Filter;

use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class InvalidArgumentExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\Filter
 */
class InvalidArgumentExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new InvalidArgumentException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException', $e);
    }
}
