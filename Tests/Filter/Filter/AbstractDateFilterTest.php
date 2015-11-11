<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter;

use Da2e\FiltrationBundle\Filter\Filter\AbstractDateFilter;

/**
 * Class AbstractDateFilterTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter
 */
class AbstractDateFilterTest extends AbstractFilterTestCase
{
    public function testFormFieldTypeProperty()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractDateFilter',
            'formFieldType'
        );

        $this->assertSame('date', $property->getValue($abstractDateFilterMock));
    }

    public function testFormFieldTypeRangedFromProperty()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractDateFilter',
            'formFieldTypeRangedFrom'
        );

        $this->assertSame('date', $property->getValue($abstractDateFilterMock));
    }

    public function testFormFieldTypeRangedToProperty()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock();
        $property = $this->getPrivateProperty(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractDateFilter',
            'formFieldTypeRangedTo'
        );

        $this->assertSame('date', $property->getValue($abstractDateFilterMock));
    }

    public function testAppendFormOptions()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock(['appendRangedFormFields']);
        $abstractDateFilterMock->expects($this->once())->method('appendRangedFormFields');
        $formBuilderMock = $this->getFormBuilderMock();

        // Defaults to ranged
        $abstractDateFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    public function testAppendFormOptions_Ranged()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock(['appendRangedFormFields']);
        $abstractDateFilterMock->expects($this->once())->method('appendRangedFormFields');
        $abstractDateFilterMock->setSingle(false);

        $formBuilderMock = $this->getFormBuilderMock();

        // Defaults to ranged
        $abstractDateFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    public function testAppendFormOptions_Single()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock(['appendSingleFormFields']);
        $abstractDateFilterMock->expects($this->once())->method('appendSingleFormFields');
        $abstractDateFilterMock->setSingle(true);

        $formBuilderMock = $this->getFormBuilderMock();

        // Defaults to ranged
        $abstractDateFilterMock->appendFormFieldsToForm($formBuilderMock);
    }

    public function testConvertSingleValue()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock();
        $abstractDateFilterMock->setValue(new \DateTime('2015-01-01 12:33:33'));

        $result = $this->invokeMethod($abstractDateFilterMock, 'convertSingleValue');
        $this->assertInstanceOf('\DateTime', $result);
        $this->assertSame('2015-01-01 00:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function testConvertSingleValue_NoValue()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock();

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
            1,
            1.0,
            -1,
            0,
        ];

        foreach ($args as $arg) {
            $abstractDateFilterMock->setValue($arg);
            $this->assertNull($this->invokeMethod($abstractDateFilterMock, 'convertSingleValue'));
        }
    }

    public function testConvertRangedValue()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock();
        $args = [
            [
                [new \DateTime('2015-01-01 12:33:33'), '\DateTime', '2015-01-01 00:00:00'],
                [null, null, null],
            ],
            [
                [null, null, null],
                [new \DateTime('2015-01-02 12:33:33'), '\DateTime', '2015-01-02 00:00:00'],
            ],
            [
                [new \DateTime('2015-01-03 12:33:33'), '\DateTime', '2015-01-03 00:00:00'],
                [new \DateTime('2015-01-04 12:33:33'), '\DateTime', '2015-01-04 00:00:00'],
            ],
        ];

        foreach ($args as $arg) {
            $abstractDateFilterMock->setFromValue($arg[0][0]);
            $abstractDateFilterMock->setToValue($arg[1][0]);

            $result = $this->invokeMethod($abstractDateFilterMock, 'convertRangedValue');
            $this->assertTrue(is_array($result));

            if ($arg[0][1] !== null) {
                $this->assertInstanceOf($arg[0][1], $result[0]);
                $this->assertSame($arg[0][2], $result[0]->format('Y-m-d H:i:s'));
            } else {
                $this->assertNull($result[0]);
            }

            if ($arg[1][1] !== null) {
                $this->assertInstanceOf($arg[1][1], $result[1]);
                $this->assertSame($arg[1][2], $result[1]->format('Y-m-d H:i:s'));
            } else {
                $this->assertNull($result[1]);
            }
        }
    }

    public function testConvertRangedValue_NoValue()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock();

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
            [1, 1],
            [1.1, 1.1],
            [-1, -1.0],
            [0, 0],
        ];

        foreach ($args as $arg) {
            $abstractDateFilterMock->setFromValue($arg[0]);
            $abstractDateFilterMock->setToValue($arg[1]);

            $result = $this->invokeMethod($abstractDateFilterMock, 'convertRangedValue');
            $this->assertSame([null, null], $result);
        }
    }

    public function testAppendSingleFormFields()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock(['getValuePropertyName',]);
        $abstractDateFilterMock->expects($this->atLeastOnce())->method('getValuePropertyName')->willReturn('bar');

        $abstractDateFilterMock->setFormFieldType('baz');
        $abstractDateFilterMock->setTitle('foo');

        // Check that the user form options will not be merged with the default ones,
        // because form options for single widget must be placed as array under the "single" key.
        $abstractDateFilterMock->setFormOptions([
            'label'         => '1',
            'required'      => true,
            'property_path' => 2,
        ]);

        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('bar', 'baz', [
            'label'         => 'foo',
            'widget'        => 'single_text',
            'format'        => 'dd/MM/yyyy',
            'required'      => false,
            'property_path' => 'value',
        ]);

        $this->invokeMethod($abstractDateFilterMock, 'appendSingleFormFields', [$formBuilderMock]);
    }

    public function testAppendSingleFormFields_CustomFormOptions()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock(['getValuePropertyName',]);
        $abstractDateFilterMock->expects($this->atLeastOnce())->method('getValuePropertyName')->willReturn('bar');

        $abstractDateFilterMock->setFormFieldType('baz');
        $abstractDateFilterMock->setTitle('foo');

        $abstractDateFilterMock->setFormOptions([
            'single' => [
                'label'         => 'foo2',
                'widget'        => 'single_text2',
                'format'        => 'yyyy/MM/dd',
                'required'      => true,
                'property_path' => 'value2',
                'attr'          => ['class' => 'foo'],
            ],
        ]);

        $formBuilderMock = $this->getFormBuilderMock(['add']);
        $formBuilderMock->expects($this->once())->method('add')->with('bar', 'baz', [
            'label'         => 'foo2',
            'widget'        => 'single_text2',
            'format'        => 'yyyy/MM/dd',
            'required'      => true,
            'property_path' => 'value',
            'attr'          => ['class' => 'foo'],
        ]);

        $this->invokeMethod($abstractDateFilterMock, 'appendSingleFormFields', [$formBuilderMock]);
    }

    public function testAppendRangedFormFields()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock([
            'getFromValuePropertyName',
            'getToValuePropertyName',
        ]);

        $abstractDateFilterMock->expects($this->atLeastOnce())->method('getFromValuePropertyName')->willReturn('foo1');
        $abstractDateFilterMock->expects($this->atLeastOnce())->method('getToValuePropertyName')->willReturn('bar1');

        $abstractDateFilterMock->setFormFieldTypeRangedFrom('foo2');
        $abstractDateFilterMock->setFormFieldTypeRangedTo('bar2');

        // Check that the user form options will not be merged with the default ones,
        // because form options for ranged widgets must be placed as array under the "ranged_from" and "ranged_to" keys.
        $abstractDateFilterMock->setFormOptions([
            'label'         => '1',
            'required'      => false,
            'property_path' => 2,
        ]);

        $formBuilderMock = $this->getFormBuilderMock(['add']);

        $formBuilderMock->expects($this->at(0))->method('add')->with('foo1', 'foo2', [
            'label'         => 'da2e.filtration.date_filter.ranged.from.label',
            'widget'        => 'single_text',
            'format'        => 'dd/MM/yyyy',
            'required'      => false,
            'property_path' => 'fromValue',
        ]);

        $formBuilderMock->expects($this->at(1))->method('add')->with('bar1', 'bar2', [
            'label'         => 'da2e.filtration.date_filter.ranged.to.label',
            'widget'        => 'single_text',
            'format'        => 'dd/MM/yyyy',
            'required'      => false,
            'property_path' => 'toValue',
        ]);

        $this->invokeMethod($abstractDateFilterMock, 'appendRangedFormFields', [$formBuilderMock]);
    }

    public function testAppendRangedFormFields_CustomFormOptions()
    {
        $abstractDateFilterMock = $this->getAbstractDateFilterMock([
            'getFromValuePropertyName',
            'getToValuePropertyName',
        ]);

        $abstractDateFilterMock->expects($this->atLeastOnce())->method('getFromValuePropertyName')->willReturn('foo1');
        $abstractDateFilterMock->expects($this->atLeastOnce())->method('getToValuePropertyName')->willReturn('bar1');

        $abstractDateFilterMock->setFormFieldTypeRangedFrom('foo2');
        $abstractDateFilterMock->setFormFieldTypeRangedTo('bar2');

        $abstractDateFilterMock->setFormOptions([
            'ranged_from' => [
                'label'         => 'foo3',
                'widget'        => 'single_text2',
                'format'        => 'yyyy/MM/dd1',
                'required'      => true,
                'property_path' => 'foo4',
                'attr'          => ['class' => 'foo'],
            ],
            'ranged_to'   => [
                'label'         => 'bar3',
                'widget'        => 'single_text',
                'format'        => 'yyyy/MM/dd2',
                'required'      => true,
                'property_path' => 'bar4',
                'attr'          => ['class' => 'bar'],
            ],
        ]);

        $formBuilderMock = $this->getFormBuilderMock(['add']);

        $formBuilderMock->expects($this->at(0))->method('add')->with('foo1', 'foo2', [
            'label'         => 'foo3',
            'widget'        => 'single_text2',
            'format'        => 'yyyy/MM/dd1',
            'required'      => true,
            'property_path' => 'fromValue',
            'attr'          => ['class' => 'foo'],
        ]);

        $formBuilderMock->expects($this->at(1))->method('add')->with('bar1', 'bar2', [
            'label'         => 'bar3',
            'widget'        => 'single_text',
            'format'        => 'yyyy/MM/dd2',
            'required'      => true,
            'property_path' => 'toValue',
            'attr'          => ['class' => 'bar'],
        ]);

        $this->invokeMethod($abstractDateFilterMock, 'appendRangedFormFields', [$formBuilderMock]);
    }

    /**
     * Gets AbstractDateFilter mock object.
     *
     * @param bool|array $methods
     * @param string     $name
     *
     * @return AbstractDateFilter|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getAbstractDateFilterMock($methods = false, $name = 'name')
    {
        return $this->getAbstractFilterMock(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractDateFilter',
            $methods,
            [$name]
        );
    }
}
