<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Executor;

use Da2e\FiltrationBundle\Exception\Filter\Executor\FilterExecutorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterExecutorExceptionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterExecutorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FilterExecutorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
