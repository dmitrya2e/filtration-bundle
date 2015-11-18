<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\FilterOption;

use Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterOptionExceptionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterOptionExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FilterOptionException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
