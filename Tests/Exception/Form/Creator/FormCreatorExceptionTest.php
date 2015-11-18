<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Exception\Form\Creator;

use Da2e\FiltrationBundle\Exception\Form\Creator\FormCreatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FormCreatorExceptionTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FormCreatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FormCreatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
