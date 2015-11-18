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

use Da2e\FiltrationBundle\Exception\Filter\Filter\LogicException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class LogicExceptionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class LogicExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new LogicException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException', $e);
    }
}
