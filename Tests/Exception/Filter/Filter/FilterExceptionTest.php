<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Filter;

use Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\Filter
 */
class FilterExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FilterException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
