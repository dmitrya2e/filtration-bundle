<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Executor;

use Da2e\FiltrationBundle\Exception\Filter\Executor\FilterExecutorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterExecutorExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Filter\Executor
 */
class FilterExecutorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FilterExecutorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
