<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Filter;

use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidHandlerException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class InvalidHandlerExceptionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class InvalidHandlerExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new InvalidHandlerException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException', $e);
    }
}
