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

use Da2e\FiltrationBundle\Filter\Filter\AbstractChoiceFilter;

/**
 * Class AbstractChoiceFilterTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class AbstractChoiceFilterTest extends AbstractFilterTestCase
{
    public function testFormFieldTypeProperty()
    {
        $abstractChoiceFilterMock = $this->getAbstractChoiceFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractChoiceFilter',
            'formFieldType'
        );

        $this->assertSame('choice', $property->getValue($abstractChoiceFilterMock));
    }

    public function testConvertValue()
    {
        $abstractChoiceFilterMock = $this->getAbstractChoiceFilterMock();

        $abstractChoiceFilterMock->setValue(['1', '2', 2.2, 'foo', 5]);
        $result = $this->invokeMethod($abstractChoiceFilterMock, 'convertValue');
        $this->assertSame([1, 2, 2, 0, 5], $result);

        $abstractChoiceFilterMock->setValue(123);
        $result = $this->invokeMethod($abstractChoiceFilterMock, 'convertValue');
        $this->assertSame([123], $result);

        $abstractChoiceFilterMock->setValue('123');
        $result = $this->invokeMethod($abstractChoiceFilterMock, 'convertValue');
        $this->assertSame([123], $result);

        $abstractChoiceFilterMock->setValue('foo');
        $result = $this->invokeMethod($abstractChoiceFilterMock, 'convertValue');
        $this->assertSame([0], $result);
    }

    public function testAppendFormFieldsToForm()
    {
        $abstractChoiceFilterMock = $this->getAbstractChoiceFilterMock(['getValuePropertyName']);
        $abstractChoiceFilterMock->expects($this->once())->method('getValuePropertyName')->willReturn('foo');

        $abstractChoiceFilterMock->setFormFieldType('bar');
        $abstractChoiceFilterMock->setTitle('baz');
        $abstractChoiceFilterMock->setDefaultValue([]);

        // Note that property_path is not changed.
        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('foo', 'bar', [
            'choices'       => [],
            'expanded'      => true,
            'multiple'      => true,
            'required'      => false,
            'label'         => 'baz',
            'data'          => [],
            'property_path' => 'value',
        ]);

        $abstractChoiceFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    public function testAppendFormFieldsToForm_CustomFormOptions()
    {
        $abstractChoiceFilterMock = $this->getAbstractChoiceFilterMock(['getValuePropertyName']);
        $abstractChoiceFilterMock->expects($this->once())->method('getValuePropertyName')->willReturn('foo');

        $abstractChoiceFilterMock->setFormFieldType('bar');
        $abstractChoiceFilterMock->setTitle('baz');
        $abstractChoiceFilterMock->setDefaultValue([]);
        $abstractChoiceFilterMock->setFormOptions([
            'choices'       => ['a', 'b', 'c'],
            'expanded'      => false,
            'multiple'      => false,
            'required'      => true,
            'label'         => 'label2',
            'data'          => ['a', 'b', 'c'],
            // try to change property_path
            'property_path' => 'value2',
            // add new options
            'attr'          => ['class' => 'foo'],
        ]);

        // Note that property_path is not changed.
        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('foo', 'bar', [
            'choices'       => ['a', 'b', 'c'],
            'expanded'      => false,
            'multiple'      => false,
            'required'      => true,
            'label'         => 'label2',
            'data'          => ['a', 'b', 'c'],
            'property_path' => 'value',
            'attr'          => ['class' => 'foo'],
        ]);

        $abstractChoiceFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    /**
     * Gets AbstractChoiceFilter mock object.
     *
     * @param bool|array $methods
     * @param string     $name
     *
     * @return AbstractChoiceFilter|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getAbstractChoiceFilterMock($methods = false, $name = 'name')
    {
        return $this->getAbstractFilterMock(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractChoiceFilter',
            $methods,
            [$name]
        );
    }
}
