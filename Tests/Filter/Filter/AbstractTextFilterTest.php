<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Filter\Filter;

use Da2e\FiltrationBundle\Filter\Filter\AbstractTextFilter;

/**
 * Class AbstractTextFilterTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class AbstractTextFilterTest extends AbstractFilterTestCase
{
    public function testFormFieldTypeProperty()
    {
        $abstractTextFilterMock = $this->getAbstractTextFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractTextFilter',
            'formFieldType'
        );

        $this->assertSame('text', $property->getValue($abstractTextFilterMock));
    }

    public function testConvertValue()
    {
        $abstractTextFilterMock = $this->getAbstractTextFilterMock();

        $abstractTextFilterMock->setValue(1);
        $result = $this->invokeMethod($abstractTextFilterMock, 'convertValue');
        $this->assertSame('1', $result);

        $abstractTextFilterMock->setValue(1.0);
        $result = $this->invokeMethod($abstractTextFilterMock, 'convertValue');
        $this->assertSame('1', $result);

        $abstractTextFilterMock->setValue('foobar');
        $result = $this->invokeMethod($abstractTextFilterMock, 'convertValue');
        $this->assertSame('foobar', $result);

        $abstractTextFilterMock->setValue([]);
        $result = $this->invokeMethod($abstractTextFilterMock, 'convertValue');
        $this->assertSame('', $result);

        $abstractTextFilterMock->setValue(['foo']);
        $result = $this->invokeMethod($abstractTextFilterMock, 'convertValue');
        $this->assertSame('', $result);

        $abstractTextFilterMock->setValue(new \stdClass());
        $result = $this->invokeMethod($abstractTextFilterMock, 'convertValue');
        $this->assertSame('', $result);

        $abstractTextFilterMock->setValue(true);
        $result = $this->invokeMethod($abstractTextFilterMock, 'convertValue');
        $this->assertSame('1', $result);

        $abstractTextFilterMock->setValue(false);
        $result = $this->invokeMethod($abstractTextFilterMock, 'convertValue');
        $this->assertSame('', $result);

        $abstractTextFilterMock->setValue(function () {
        });
        $result = $this->invokeMethod($abstractTextFilterMock, 'convertValue');
        $this->assertSame('', $result);
    }

    public function testAppendFormFieldsToForm()
    {
        $abstractTextFilterMock = $this->getAbstractTextFilterMock(['getValuePropertyName']);
        $abstractTextFilterMock->expects($this->once())->method('getValuePropertyName')->willReturn('foo');

        $abstractTextFilterMock->setFormFieldType('bar');
        $abstractTextFilterMock->setTitle('baz');
        $abstractTextFilterMock->setDefaultValue('');

        // Note that property_path is not changed.
        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('foo', 'bar', [
            'required'      => false,
            'label'         => 'baz',
            'data'          => '',
            'property_path' => 'value',
        ]);

        $abstractTextFilterMock->appendFormFieldsToForm($formBuilderMock);
    }


    public function testAppendFormFieldsToForm_CustomFormOptions()
    {
        $abstractTextFilterMock = $this->getAbstractTextFilterMock(['getValuePropertyName']);
        $abstractTextFilterMock->expects($this->once())->method('getValuePropertyName')->willReturn('foo');

        $abstractTextFilterMock->setFormFieldType('bar');
        $abstractTextFilterMock->setTitle('baz');
        $abstractTextFilterMock->setDefaultValue('');
        $abstractTextFilterMock->setFormOptions([
            'required'      => true,
            'label'         => 'label2',
            'data'          => 'default_value2',
            // try to change property_path
            'property_path' => 'value2',
            // add new options
            'attr'          => ['class' => 'foo'],
        ]);
        
        // Note that property_path is not changed.
        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('foo', 'bar', [
            'required'      => true,
            'label'         => 'label2',
            'data'          => 'default_value2',
            'property_path' => 'value',
            'attr'          => ['class' => 'foo',]
        ]);

        $abstractTextFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    /**
     * Gets AbstractTextFilter mock object.
     *
     * @param bool|array $methods
     * @param string     $name
     *
     * @return AbstractTextFilter|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getAbstractTextFilterMock($methods = false, $name = 'name')
    {
        return $this->getAbstractFilterMock(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractTextFilter',
            $methods,
            [$name]
        );
    }
}
