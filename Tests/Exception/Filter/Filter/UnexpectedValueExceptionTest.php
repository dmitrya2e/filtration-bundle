<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Filter;

use Da2e\FiltrationBundle\Exception\Filter\Filter\UnexpectedValueException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class UnexpectedValueExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\Filter
 */
class UnexpectedValueExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new UnexpectedValueException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException', $e);
    }
}
