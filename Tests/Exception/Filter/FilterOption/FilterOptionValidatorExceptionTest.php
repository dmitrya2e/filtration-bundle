<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\FilterOption;

use Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionValidatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterOptionValidatorExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\FilterOption
 */
class FilterOptionValidatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FilterOptionValidatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionException', $e);
    }
}
