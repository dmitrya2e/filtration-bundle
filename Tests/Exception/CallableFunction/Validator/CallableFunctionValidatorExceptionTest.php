<?php

namespace Da2e\FiltrationBundle\Tests\Exception\CallableFunction\Validator;

use Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class CallableFunctionValidatorExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\CallableFunction\Validator
 */
class CallableFunctionValidatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new CallableFunctionValidatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
