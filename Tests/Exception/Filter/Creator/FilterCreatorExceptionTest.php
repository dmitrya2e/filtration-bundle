<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Creator;

use Da2e\FiltrationBundle\Exception\Filter\Creator\FilterCreatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterCreatorExceptionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterCreatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FilterCreatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
