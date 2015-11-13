<?php

namespace Da2e\FiltrationBundle\Tests\Exception;

use Da2e\FiltrationBundle\Exception\BaseException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class BaseExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception
 */
class BaseExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new BaseException();
        $this->assertInstanceOf('\Exception', $e);
    }
}
