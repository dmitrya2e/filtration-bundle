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

use Da2e\FiltrationBundle\Filter\Filter\AbstractEntityFilter;

/**
 * Class AbstractEntityFilterTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class AbstractEntityFilterTest extends AbstractFilterTestCase
{
    public function testFormFieldTypeProperty()
    {
        $abstractEntityFilterMock = $this->getAbstractEntityFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractEntityFilter',
            'formFieldType'
        );

        $this->assertSame('entity', $property->getValue($abstractEntityFilterMock));
    }

    public function testConvertValue()
    {
        $abstractEntityFilterMock = $this->getAbstractEntityFilterMock();

        // Array of entities
        $entity1 = $this->getMockBuilder('\stdClass')->setMethods(['getId'])->getMock();
        $entity1->expects($this->once())->method('getId')->willReturn(1);

        $entity2 = $this->getMockBuilder('\stdClass')->setMethods(['getId'])->getMock();
        $entity2->expects($this->once())->method('getId')->willReturn(2);

        $abstractEntityFilterMock->setValue([
            'foo', // must be skipped, because it is not an object
            new \stdClass(), // must be skipped, because it does not have a getId() method
            $entity1,
            $entity2
        ]);

        $result = $this->invokeMethod($abstractEntityFilterMock, 'convertValue');
        $this->assertSame([1, 2], $result);

        // Empty array
        $abstractEntityFilterMock->setValue([]);

        $result = $this->invokeMethod($abstractEntityFilterMock, 'convertValue');
        $this->assertSame([], $result);

        // [TODO]: test with Doctrines Collection interface (with exactly same logic as array)

        // Single entity
        $entity3 = $this->getMockBuilder('\stdClass')->setMethods(['getId'])->getMock();
        $entity3->expects($this->once())->method('getId')->willReturn(3);

        $abstractEntityFilterMock->setValue($entity3);

        $result = $this->invokeMethod($abstractEntityFilterMock, 'convertValue');
        $this->assertSame([3], $result);

        // Single entity (no value, because object does not have getId() method)
        $abstractEntityFilterMock->setValue(new \stdClass());

        $result = $this->invokeMethod($abstractEntityFilterMock, 'convertValue');
        $this->assertSame([], $result);

        // Single entity (no value, because the value is not an object)
        $abstractEntityFilterMock->setValue('foo');

        $result = $this->invokeMethod($abstractEntityFilterMock, 'convertValue');
        $this->assertSame([], $result);
    }

    public function testAppendFormFieldsToForm()
    {
        $abstractEntityFilterMock = $this->getAbstractEntityFilterMock(['getValuePropertyName']);
        $abstractEntityFilterMock->expects($this->once())->method('getValuePropertyName')->willReturn('foo');

        $abstractEntityFilterMock->setFormFieldType('bar');
        $abstractEntityFilterMock->setTitle('baz');
        $abstractEntityFilterMock->setDefaultValue([]);

        // Note that property_path is not changed.
        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('foo', 'bar', [
            'required'      => false,
            'expanded'      => true,
            'multiple'      => true,
            'label'         => 'baz',
            'data'          => [],
            'property_path' => 'value',
        ]);

        $abstractEntityFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    public function testAppendFormFieldsToForm_CustomFormOptions()
    {
        $abstractEntityFilterMock = $this->getAbstractEntityFilterMock(['getValuePropertyName']);
        $abstractEntityFilterMock->expects($this->once())->method('getValuePropertyName')->willReturn('foo');

        $abstractEntityFilterMock->setFormFieldType('bar');
        $abstractEntityFilterMock->setTitle('baz');
        $abstractEntityFilterMock->setDefaultValue([]);
        $abstractEntityFilterMock->setFormOptions([
            'required'      => true,
            'expanded'      => false,
            'multiple'      => false,
            'label'         => 'label2',
            // try to change property_path
            'property_path' => 'value2',
            // add new options
            'data'          => ['a', 'b', 'c'],
            'attr'          => ['class' => 'foo'],
        ]);

        // Note that property_path is not changed.
        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('foo', 'bar', [
            'required'      => true,
            'expanded'      => false,
            'multiple'      => false,
            'label'         => 'label2',
            'property_path' => 'value',
            'data'          => ['a', 'b', 'c'],
            'attr'          => ['class' => 'foo'],
        ]);

        $abstractEntityFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    /**
     * Gets AbstractChoiceFilter mock object.
     *
     * @param bool|array $methods
     * @param string     $name
     *
     * @return AbstractEntityFilter|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getAbstractEntityFilterMock($methods = false, $name = 'name')
    {
        return $this->getAbstractFilterMock(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractEntityFilter',
            $methods,
            [$name]
        );
    }
}
