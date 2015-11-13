<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Creator;

use Da2e\FiltrationBundle\Exception\Filter\Creator\FilterCreatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterCreatorExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\Creator
 */
class FilterCreatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FilterCreatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
