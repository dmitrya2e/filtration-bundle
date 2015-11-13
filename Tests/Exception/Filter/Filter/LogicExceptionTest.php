<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Filter;

use Da2e\FiltrationBundle\Exception\Filter\Filter\LogicException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class LogicExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\Filter
 */
class LogicExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new LogicException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException', $e);
    }
}
