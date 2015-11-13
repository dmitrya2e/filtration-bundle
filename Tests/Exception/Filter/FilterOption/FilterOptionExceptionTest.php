<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\FilterOption;

use Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterOptionExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\FilterOption
 */
class FilterOptionExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FilterOptionException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
