<?php

namespace Da2e\FiltrationBundle\Tests\Exception\Form\Creator;

use Da2e\FiltrationBundle\Exception\Form\Creator\FormCreatorException;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FormCreatorExceptionTest
 * @package Da2e\FiltrationBundle\Tests\Exception\Form\Creator
 */
class FormCreatorExceptionTest extends TestCase
{
    public function testExtends()
    {
        $e = new FormCreatorException();
        $this->assertInstanceOf('\Da2e\FiltrationBundle\Exception\BaseException', $e);
    }
}
