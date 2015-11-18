<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Exception\Filter\Collection\Creator;

use Da2e\FiltrationBundle\Exception\Filter\Collection\Creator\CollectionCreatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class CollectionCreatorExceptionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class CollectionCreatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new CollectionCreatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\Filter\Collection\CollectionException', $e);
    }
}
