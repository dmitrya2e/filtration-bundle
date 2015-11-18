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

use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException;
use Da2e\FiltrationBundle\Filter\Filter\AbstractNumberFilter;

/**
 * Class AbstractNumberFilterTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class AbstractNumberFilterTest extends AbstractFilterTestCase
{
    public function testFormFieldTypeProperty()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractNumberFilter',
            'formFieldType'
        );

        $this->assertSame('number', $property->getValue($abstractNumberFilterMock));
    }

    public function testFormFieldTypeRangedFromProperty()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractNumberFilter',
            'formFieldTypeRangedFrom'
        );

        $this->assertSame('number', $property->getValue($abstractNumberFilterMock));
    }

    public function testFormFieldTypeRangedToProperty()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractNumberFilter',
            'formFieldTypeRangedTo'
        );

        $this->assertSame('number', $property->getValue($abstractNumberFilterMock));
    }

    public function testFloatProperty()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractNumberFilter',
            'float'
        );

        $this->assertFalse($property->getValue($abstractNumberFilterMock));
    }

    public function testGetValidOptions()
    {
        $this->assertTrue(is_array(AbstractNumberFilter::getValidOptions()));
        $this->assertSame(
            array_merge($this->getAbstractRangeOrSingleFilterValidOptions(), [
                'float' => [
                    'setter' => 'setFloat',
                    'empty'  => false,
                    'type'   => 'bool',
                ],
            ]),
            AbstractNumberFilter::getValidOptions()
        );
    }

    public function testAppendFormOptions()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock(['appendRangedFormFields']);
        $abstractNumberFilterMock->expects($this->once())->method('appendRangedFormFields');
        $formBuilderMock = $this->getFormBuilderMock();

        // Defaults to ranged
        $abstractNumberFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    public function testAppendFormOptions_Ranged()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock(['appendRangedFormFields']);
        $abstractNumberFilterMock->expects($this->once())->method('appendRangedFormFields');
        $abstractNumberFilterMock->setSingle(false);

        $formBuilderMock = $this->getFormBuilderMock();

        // Defaults to ranged
        $abstractNumberFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    public function testAppendFormOptions_Single()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock(['appendSingleFormFields']);
        $abstractNumberFilterMock->expects($this->once())->method('appendSingleFormFields');
        $abstractNumberFilterMock->setSingle(true);

        $formBuilderMock = $this->getFormBuilderMock();

        // Defaults to ranged
        $abstractNumberFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    public function testIsFloat()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $this->assertFalse($abstractNumberFilterMock->isFloat());
    }

    public function testSetFloat()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();

        $abstractNumberFilterMock->setFloat(false);
        $this->assertFalse($abstractNumberFilterMock->isFloat());

        $abstractNumberFilterMock->setFloat(true);
        $this->assertTrue($abstractNumberFilterMock->isFloat());
    }

    public function testSetFloat_InvalidArgument()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();

        $args = [
            '',
            1,
            1.0,
            null,
            0,
            new \stdClass(),
            [],
            function () {
            },
            'foobar',
        ];

        $exceptionCount = 0;

        foreach ($args as $arg) {
            try {
                $abstractNumberFilterMock->setFloat($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testConvertSingleValue_HasValue_Int()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $args = [[1, 1], [1.0, 1], ['1', 1], [0, 0], ['0', 0], [-1, -1], ['-1', -1]];

        foreach ($args as $arg) {
            $abstractNumberFilterMock->setValue($arg[0]);
            $this->assertSame($arg[1], $this->invokeMethod($abstractNumberFilterMock, 'convertSingleValue'));
        }
    }

    public function testConvertSingleValue_HasValue_Float()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $abstractNumberFilterMock->setFloat(true);

        $args = [[1, 1.0], [1.0, 1.0], ['1', 1.0], [0, 0.0], ['0', 0.0], [-1, -1.0], ['-1', -1.0]];

        foreach ($args as $arg) {
            $abstractNumberFilterMock->setValue($arg[0]);
            $this->assertSame($arg[1], $this->invokeMethod($abstractNumberFilterMock, 'convertSingleValue'));
        }
    }

    public function testConvertSingleValue_NoValue()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $abstractNumberFilterMock->setFloat(true);

        $args = [
            '',
            null,
            new \stdClass(),
            [],
            function () {
            },
            'foobar',
            true,
            false,
        ];

        foreach ($args as $arg) {
            $abstractNumberFilterMock->setValue($arg);
            $this->assertNull($this->invokeMethod($abstractNumberFilterMock, 'convertSingleValue'));
        }
    }

    public function testConvertRangedValue_HasValue_Int()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $args = [
            [[1, 1], [null, null]],
            [[1.0, 1], [null, null]],
            [[null, null], ['1', 1]],
            [[null, null], [0, 0]],
            [['0', 0], ['0', 0]],
            [[-1, -1], [-1, -1]],
            [['-1', -1], [null, null]],
        ];

        foreach ($args as $arg) {
            $abstractNumberFilterMock->setFromValue($arg[0][0]);
            $abstractNumberFilterMock->setToValue($arg[1][0]);

            $result = $this->invokeMethod($abstractNumberFilterMock, 'convertRangedValue');
            $this->assertSame([$arg[0][1], $arg[1][1]], $result);
        }
    }

    public function testConvertRangedValue_HasValue_Float()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $abstractNumberFilterMock->setFloat(true);

        $args = [
            [[1, 1.0], [null, null]],
            [[1.0, 1.0], [null, null]],
            [[null, null], ['1', 1.0]],
            [[null, null], [0, 0.0]],
            [['0', 0.0], ['0', 0.0]],
            [[-1, -1.0], [-1, -1.0]],
            [['-1', -1.0], [null, null]],
        ];

        foreach ($args as $arg) {
            $abstractNumberFilterMock->setFromValue($arg[0][0]);
            $abstractNumberFilterMock->setToValue($arg[1][0]);

            $result = $this->invokeMethod($abstractNumberFilterMock, 'convertRangedValue');
            $this->assertSame([$arg[0][1], $arg[1][1]], $result);
        }
    }

    public function testConvertRangedValue_NoValue()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock();
        $abstractNumberFilterMock->setFloat(true);

        $args = [
            ['', ''],
            [null, null],
            [new \stdClass(), new \stdClass()],
            [[], []],
            [
                function () {
                },
                function () {
                }
            ],
            ['foobar', 'foobar'],
            [true, true],
            [false, false],
        ];

        foreach ($args as $arg) {
            $abstractNumberFilterMock->setFromValue($arg[0]);
            $abstractNumberFilterMock->setToValue($arg[1]);

            $result = $this->invokeMethod($abstractNumberFilterMock, 'convertRangedValue');
            $this->assertSame([null, null], $result);
        }
    }

    public function testAppendSingleFormFields()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock(['getValuePropertyName',]);
        $abstractNumberFilterMock->expects($this->atLeastOnce())->method('getValuePropertyName')->willReturn('bar');

        $abstractNumberFilterMock->setFormFieldType('baz');
        $abstractNumberFilterMock->setTitle('foo');

        // Check that the user form options will not be merged with the default ones,
        // because form options for single widget must be placed as array under the "single" key.
        $abstractNumberFilterMock->setFormOptions([
            'label'         => '1',
            'required'      => false,
            'property_path' => 2,
        ]);

        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('bar', 'baz', [
            'label'         => 'foo',
            'required'      => false,
            'property_path' => 'value',
        ]);

        $this->invokeMethod($abstractNumberFilterMock, 'appendSingleFormFields', [$formBuilderMock]);
    }

    public function testAppendSingleFormFields_CustomFormOptions()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock(['getValuePropertyName',]);
        $abstractNumberFilterMock->expects($this->atLeastOnce())->method('getValuePropertyName')->willReturn('bar');

        $abstractNumberFilterMock->setFormFieldType('baz');
        $abstractNumberFilterMock->setTitle('foo');

        $abstractNumberFilterMock->setFormOptions([
            'single' => [
                'label'         => 'foo2',
                'required'      => true,
                'property_path' => 'value2',
                'attr'          => ['class' => 'foo'],
            ],
        ]);

        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('bar', 'baz', [
            'label'         => 'foo2',
            'required'      => true,
            'property_path' => 'value',
            'attr'          => ['class' => 'foo'],
        ]);

        $this->invokeMethod($abstractNumberFilterMock, 'appendSingleFormFields', [$formBuilderMock]);
    }

    public function testAppendRangedFormFields()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock([
            'getFromValuePropertyName',
            'getToValuePropertyName',
        ]);

        $abstractNumberFilterMock->expects($this->atLeastOnce())->method('getFromValuePropertyName')->willReturn('foo1');
        $abstractNumberFilterMock->expects($this->atLeastOnce())->method('getToValuePropertyName')->willReturn('bar1');

        $abstractNumberFilterMock->setFormFieldTypeRangedFrom('foo2');
        $abstractNumberFilterMock->setFormFieldTypeRangedTo('bar2');

        // Check that the user form options will not be merged with the default ones,
        // because form options for ranged widgets must be placed as array under the "ranged_from" and "ranged_to" keys.
        $abstractNumberFilterMock->setFormOptions([
            'label'         => '1',
            'required'      => false,
            'property_path' => 2,
        ]);

        $formBuilderMock = $this->getFormBuilderMock(['add']);

        $formBuilderMock->expects($this->at(0))->method('add')->with('foo1', 'foo2', [
            'label'         => 'da2e.filtration.number_filter.ranged.from.label',
            'required'      => false,
            'property_path' => 'fromValue',
        ]);

        $formBuilderMock->expects($this->at(1))->method('add')->with('bar1', 'bar2', [
            'label'         => 'da2e.filtration.number_filter.ranged.to.label',
            'required'      => false,
            'property_path' => 'toValue',
        ]);

        $this->invokeMethod($abstractNumberFilterMock, 'appendRangedFormFields', [$formBuilderMock]);
    }

    public function testAppendRangedFormFields_CustomFormOptions()
    {
        $abstractNumberFilterMock = $this->getAbstractNumberFilterMock([
            'getFromValuePropertyName',
            'getToValuePropertyName',
        ]);

        $abstractNumberFilterMock->expects($this->atLeastOnce())->method('getFromValuePropertyName')->willReturn('foo1');
        $abstractNumberFilterMock->expects($this->atLeastOnce())->method('getToValuePropertyName')->willReturn('bar1');

        $abstractNumberFilterMock->setFormFieldTypeRangedFrom('foo2');
        $abstractNumberFilterMock->setFormFieldTypeRangedTo('bar2');

        $abstractNumberFilterMock->setFormOptions([
            'ranged_from' => [
                'label'         => 'foo3',
                'required'      => true,
                'property_path' => 'foo4',
                'attr'          => ['class' => 'foo'],
            ],
            'ranged_to'   => [
                'label'         => 'bar3',
                'required'      => true,
                'property_path' => 'bar4',
                'attr'          => ['class' => 'bar'],
            ],
        ]);

        $formBuilderMock = $this->getFormBuilderMock(['add']);

        $formBuilderMock->expects($this->at(0))->method('add')->with('foo1', 'foo2', [
            'label'         => 'foo3',
            'required'      => true,
            'property_path' => 'fromValue',
            'attr'          => ['class' => 'foo'],
        ]);

        $formBuilderMock->expects($this->at(1))->method('add')->with('bar1', 'bar2', [
            'label'         => 'bar3',
            'required'      => true,
            'property_path' => 'toValue',
            'attr'          => ['class' => 'bar'],
        ]);

        $this->invokeMethod($abstractNumberFilterMock, 'appendRangedFormFields', [$formBuilderMock]);
    }

    /**
     * Gets AbstractNumberFilter mock object.
     *
     * @param bool|array $methods
     * @param string     $name
     *
     * @return AbstractNumberFilter|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getAbstractNumberFilterMock($methods = false, $name = 'name')
    {
        return $this->getAbstractFilterMock(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractNumberFilter',
            $methods,
            [$name]
        );
    }
}
