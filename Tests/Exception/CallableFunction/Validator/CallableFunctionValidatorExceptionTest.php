<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Exception\CallableFunction\Validator;

use Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class CallableFunctionValidatorExceptionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class CallableFunctionValidatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new CallableFunctionValidatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
