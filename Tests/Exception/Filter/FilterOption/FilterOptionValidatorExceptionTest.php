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

use Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionValidatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterOptionValidatorExceptionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterOptionValidatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FilterOptionValidatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionException', $e);
    }
}
