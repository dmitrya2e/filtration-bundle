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
use Da2e\FiltrationBundle\Filter\Filter\AbstractRangeOrSingleFilter;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * Class AbstractRangeOrSingleFilterTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class AbstractRangeOrSingleFilterTest extends AbstractFilterTestCase
{
    public function testConvertValue()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock([
            'convertSingleValue',
            'convertRangedValue',
        ]);

        $abstractRangeOrSingleFilterMock->expects($this->once())->method('convertSingleValue')->willReturn('single');
        $abstractRangeOrSingleFilterMock->expects($this->exactly(2))->method('convertRangedValue')->willReturn('ranged');

        // Defaults to ranged type
        $this->assertSame('ranged', $this->invokeMethod($abstractRangeOrSingleFilterMock, 'convertValue'));

        // Set single type
        $abstractRangeOrSingleFilterMock->setSingle(true);
        $this->assertSame('single', $this->invokeMethod($abstractRangeOrSingleFilterMock, 'convertValue'));

        // Set ranged type
        $abstractRangeOrSingleFilterMock->setSingle(false);
        $this->assertSame('ranged', $this->invokeMethod($abstractRangeOrSingleFilterMock, 'convertValue'));
    }

    public function testGetValidOptions()
    {
        $this->assertTrue(is_array(AbstractRangeOrSingleFilter::getValidOptions()));
        $this->assertSame($this->getAbstractRangeOrSingleFilterValidOptions(), AbstractRangeOrSingleFilter::getValidOptions());
    }

    public function testHasAppliedValue()
    {
        //======================================================================
        // Default values
        //======================================================================
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        // Defaults to ranged type
        $this->assertFalse($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Set single type
        $abstractRangeOrSingleFilterMock->setSingle(true);
        $this->assertFalse($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Set ranged type
        $abstractRangeOrSingleFilterMock->setSingle(false);
        $this->assertFalse($abstractRangeOrSingleFilterMock->hasAppliedValue());

        //======================================================================
        // Mocked values
        //======================================================================
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock([
            'getConvertedFromValue',
            'getConvertedToValue',
            'getConvertedValue'
        ]);

        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedFromValue')->willReturn(null);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedToValue')->willReturn(null);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedValue')->willReturn(null);

        // Defaults to ranged type
        $this->assertFalse($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Set single type
        $abstractRangeOrSingleFilterMock->setSingle(true);
        $this->assertFalse($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Set ranged type
        $abstractRangeOrSingleFilterMock->setSingle(false);
        $this->assertFalse($abstractRangeOrSingleFilterMock->hasAppliedValue());
    }

    public function testHasAppliedValue_True()
    {
        $rangedMockedMethods = ['getConvertedFromValue', 'getConvertedToValue'];

        // Defaults to ranged type
        // "From value" has value
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock($rangedMockedMethods);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedFromValue')->willReturn('foo');
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedToValue')->willReturn(null);
        $this->assertTrue($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Defaults to ranged type
        // "To value" has value
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock($rangedMockedMethods);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedFromValue')->willReturn(null);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedToValue')->willReturn('foo');
        $this->assertTrue($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Defaults to ranged type
        // Both ranges has value
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock($rangedMockedMethods);
        $abstractRangeOrSingleFilterMock->setSingle(false);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedFromValue')->willReturn('foo');
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedToValue')->willReturn('bar');
        $this->assertTrue($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Set single type
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock(['getConvertedValue']);
        $abstractRangeOrSingleFilterMock->setSingle(true);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedValue')->willReturn('foobar');
        $this->assertTrue($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Set ranged type
        // "From value" has value
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock($rangedMockedMethods);
        $abstractRangeOrSingleFilterMock->setSingle(false);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedFromValue')->willReturn('foo');
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedToValue')->willReturn(null);
        $this->assertTrue($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Set ranged type
        // "To value" has value
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock($rangedMockedMethods);
        $abstractRangeOrSingleFilterMock->setSingle(false);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedFromValue')->willReturn(null);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedToValue')->willReturn('foo');
        $this->assertTrue($abstractRangeOrSingleFilterMock->hasAppliedValue());

        // Set ranged type
        // Both ranges has value
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock($rangedMockedMethods);
        $abstractRangeOrSingleFilterMock->setSingle(false);
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedFromValue')->willReturn('foo');
        $abstractRangeOrSingleFilterMock->expects($this->any())->method('getConvertedToValue')->willReturn('bar');
        $this->assertTrue($abstractRangeOrSingleFilterMock->hasAppliedValue());
    }

    public function testHasAppliedValue_CustomFunction_True()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock(['getConvertedValueFrom']);
        $abstractRangeOrSingleFilterMock->setHasAppliedValueFunction(function(FilterInterface $filter) {
            return $filter->getConvertedValueFrom() === 'foobar';
        });

        $abstractRangeOrSingleFilterMock->expects($this->once())->method('getConvertedValueFrom')->willReturn('barfoo');
        $this->assertFalse($abstractRangeOrSingleFilterMock->hasAppliedValue());
    }

    public function testHasAppliedValue_CustomFunction_False()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock(['getConvertedValueFrom']);
        $abstractRangeOrSingleFilterMock->setHasAppliedValueFunction(function(FilterInterface $filter) {
            return $filter->getConvertedValueFrom() === 'foobar';
        });

        $abstractRangeOrSingleFilterMock->expects($this->once())->method('getConvertedValueFrom')->willReturn('foobar');
        $this->assertTrue($abstractRangeOrSingleFilterMock->hasAppliedValue());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException
     */
    public function testHasAppliedValue_CustomFunction_ExceptionOnInvalidReturnValue()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock(['getConvertedValue']);
        $abstractRangeOrSingleFilterMock->setHasAppliedValueFunction(function(FilterInterface $filter) {
            return 'foo';
        });

        $abstractRangeOrSingleFilterMock->hasAppliedValue();
    }

    public function testGetFromValue()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertNull($abstractRangeOrSingleFilterMock->getFromValue());
    }

    public function testSetFromValue()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setFromValue('foobar');
        $this->assertSame('foobar', $abstractRangeOrSingleFilterMock->getFromValue());
    }

    public function testGetToValue()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertNull($abstractRangeOrSingleFilterMock->getToValue());
    }

    public function testSetToValue()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setToValue('foobar');
        $this->assertSame('foobar', $abstractRangeOrSingleFilterMock->getToValue());
    }

    public function testGetConvertedToValue()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertNull($abstractRangeOrSingleFilterMock->getConvertedFromValue());
    }

    public function testGetConvertedToValue_Converted()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock(['executeValueConversion']);
        $abstractRangeOrSingleFilterMock->expects($this->once())->method('executeValueConversion')->willReturn(['foo', 'bar']);

        $this->assertSame('bar', $abstractRangeOrSingleFilterMock->getConvertedToValue());
        $this->assertSame('bar', $abstractRangeOrSingleFilterMock->getConvertedToValue());
        $this->assertSame('foo', $abstractRangeOrSingleFilterMock->getConvertedFromValue());
    }

    public function testGetConvertedFromValue()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertNull($abstractRangeOrSingleFilterMock->getConvertedToValue());
    }

    public function testGetConvertedFromValue_Converted()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock(['executeValueConversion']);
        $abstractRangeOrSingleFilterMock->expects($this->once())->method('executeValueConversion')->willReturn(['foo', 'bar']);

        $this->assertSame('foo', $abstractRangeOrSingleFilterMock->getConvertedFromValue());
        $this->assertSame('foo', $abstractRangeOrSingleFilterMock->getConvertedFromValue());
        $this->assertSame('bar', $abstractRangeOrSingleFilterMock->getConvertedToValue());
    }

    public function testIsSingle()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertFalse($abstractRangeOrSingleFilterMock->isSingle());
    }

    public function testSetSingle()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        $abstractRangeOrSingleFilterMock->setSingle(false);
        $this->assertFalse($abstractRangeOrSingleFilterMock->isSingle());

        $abstractRangeOrSingleFilterMock->setSingle(true);
        $this->assertTrue($abstractRangeOrSingleFilterMock->isSingle());
    }

    public function testSetSingle_InvalidArgument()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

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
                $abstractRangeOrSingleFilterMock->setSingle($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetSingleType()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertSame(
            AbstractRangeOrSingleFilter::SINGLE_TYPE_EXACT,
            $abstractRangeOrSingleFilterMock->getSingleType()
        );
    }

    public function testSetSingleType()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        $args = [
            AbstractRangeOrSingleFilter::SINGLE_TYPE_EXACT,
            AbstractRangeOrSingleFilter::SINGLE_TYPE_GREATER,
            AbstractRangeOrSingleFilter::SINGLE_TYPE_GREATER_OR_EQUAL,
            AbstractRangeOrSingleFilter::SINGLE_TYPE_LESS,
            AbstractRangeOrSingleFilter::SINGLE_TYPE_LESS_OR_EQUAL,
        ];

        foreach ($args as $arg) {
            $abstractRangeOrSingleFilterMock->setSingleType($arg);
            $this->assertSame($arg, $abstractRangeOrSingleFilterMock->getSingleType());
        }
    }

    public function testSetSingleType_InvalidArgument()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

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
            true,
            false,
        ];

        $exceptionCount = 0;

        foreach ($args as $arg) {
            try {
                $abstractRangeOrSingleFilterMock->setSingleType($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetFromValuePropertyName()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertSame('fromValue', $abstractRangeOrSingleFilterMock->getFromValuePropertyName());
    }

    public function testSetFromValuePropertyName()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setFromValuePropertyName('fromValue');
        $this->assertSame('fromValue', $abstractRangeOrSingleFilterMock->getFromValuePropertyName());
    }

    public function testSetFromValuePropertyName_InvalidArg_NotString()
    {
        $args = [
            1,
            1.0,
            null,
            0,
            new \stdClass(),
            [],
            function () {
            },
            true,
            false,
            '',
        ];

        $exceptionCount = 0;
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        foreach ($args as $arg) {
            try {
                $abstractRangeOrSingleFilterMock->setFromValuePropertyName($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException
     */
    public function testSetFromValuePropertyName_InvalidArg_NonExistantProperty()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setToValuePropertyName('foobar');
    }

    public function testGetToValuePropertyName()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertSame('toValue', $abstractRangeOrSingleFilterMock->getToValuePropertyName());
    }

    public function testSetToValuePropertyName()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setToValuePropertyName('toValue');
        $this->assertSame('toValue', $abstractRangeOrSingleFilterMock->getToValuePropertyName());
    }

    public function testSetToValuePropertyName_InvalidArg_NotString()
    {
        $args = [
            1,
            1.0,
            null,
            0,
            new \stdClass(),
            [],
            function () {
            },
            true,
            false,
            '',
        ];

        $exceptionCount = 0;
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        foreach ($args as $arg) {
            try {
                $abstractRangeOrSingleFilterMock->setToValuePropertyName($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException
     */
    public function testSetToValuePropertyName_InvalidArg_NonExistantProperty()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setToValuePropertyName('foobar');
    }

    public function testGetFromPostfix()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertSame('_from', $abstractRangeOrSingleFilterMock->getFromPostfix());
    }

    public function testSetFromPostfix()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setFromPostfix('_from');
        $this->assertSame('_from', $abstractRangeOrSingleFilterMock->getFromPostfix());
    }

    public function testSetFromPostfix_InvalidArg_NotString()
    {
        $args = [
            1,
            1.0,
            null,
            0,
            new \stdClass(),
            [],
            function () {
            },
            true,
            false,
            '',
        ];

        $exceptionCount = 0;
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        foreach ($args as $arg) {
            try {
                $abstractRangeOrSingleFilterMock->setFromPostfix($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetToPostfix()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertSame('_to', $abstractRangeOrSingleFilterMock->getToPostfix());
    }

    public function testSetToPostfix()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setToPostfix('_to');
        $this->assertSame('_to', $abstractRangeOrSingleFilterMock->getToPostfix());
    }

    public function testSetToPostfix_InvalidArg_NotString()
    {
        $args = [
            1,
            1.0,
            null,
            0,
            new \stdClass(),
            [],
            function () {
            },
            true,
            false,
            '',
        ];

        $exceptionCount = 0;
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        foreach ($args as $arg) {
            try {
                $abstractRangeOrSingleFilterMock->setToPostfix($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetFormFieldTypeRangedFrom()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertSame('', $abstractRangeOrSingleFilterMock->getFormFieldTypeRangedFrom());
    }

    public function testSetFormFieldTypeRangedFrom()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setFormFieldTypeRangedFrom('foo');
        $this->assertSame('foo', $abstractRangeOrSingleFilterMock->getFormFieldTypeRangedFrom());
    }

    public function testSetFormFieldTypeRangedFrom_InvalidArg_NotString()
    {
        $args = [
            1,
            1.0,
            null,
            0,
            new \stdClass(),
            [],
            function () {
            },
            true,
            false,
            '',
        ];

        $exceptionCount = 0;
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        foreach ($args as $arg) {
            try {
                $abstractRangeOrSingleFilterMock->setFormFieldTypeRangedFrom($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetToFieldTypeRangedFrom()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertSame('', $abstractRangeOrSingleFilterMock->getFormFieldTypeRangedTo());
    }

    public function testSetToFieldTypeRangedFrom()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $abstractRangeOrSingleFilterMock->setFormFieldTypeRangedTo('foo');
        $this->assertSame('foo', $abstractRangeOrSingleFilterMock->getFormFieldTypeRangedTo());
    }

    public function testSetToFieldTypeRangedFrom_InvalidArg_NotString()
    {
        $args = [
            1,
            1.0,
            null,
            0,
            new \stdClass(),
            [],
            function () {
            },
            true,
            false,
            '',
        ];

        $exceptionCount = 0;
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        foreach ($args as $arg) {
            try {
                $abstractRangeOrSingleFilterMock->setFormFieldTypeRangedTo($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetRangedFromType()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertSame(
            AbstractRangeOrSingleFilter::RANGED_FROM_TYPE_GREATER_OR_EQUAL,
            $abstractRangeOrSingleFilterMock->getRangedFromType()
        );
    }

    public function testSetRangedFromType()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        $args = [
            AbstractRangeOrSingleFilter::RANGED_FROM_TYPE_GREATER,
            AbstractRangeOrSingleFilter::RANGED_FROM_TYPE_GREATER_OR_EQUAL,
        ];

        foreach ($args as $arg) {
            $abstractRangeOrSingleFilterMock->setRangedFromType($arg);
            $this->assertSame($arg, $abstractRangeOrSingleFilterMock->getRangedFromType());
        }
    }

    public function testSetRangedFromType_InvalidArgument()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

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
            true,
            false,
            AbstractRangeOrSingleFilter::RANGED_TO_TYPE_LESS,
            AbstractRangeOrSingleFilter::RANGED_TO_TYPE_LESS_OR_EQUAL,
        ];

        $exceptionCount = 0;

        foreach ($args as $arg) {
            try {
                $abstractRangeOrSingleFilterMock->setRangedFromType($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetRangedToType()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();
        $this->assertSame(
            AbstractRangeOrSingleFilter::RANGED_TO_TYPE_LESS_OR_EQUAL,
            $abstractRangeOrSingleFilterMock->getRangedToType()
        );
    }

    public function testSetRangedToType()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

        $args = [
            AbstractRangeOrSingleFilter::RANGED_TO_TYPE_LESS,
            AbstractRangeOrSingleFilter::RANGED_TO_TYPE_LESS_OR_EQUAL,
        ];

        foreach ($args as $arg) {
            $abstractRangeOrSingleFilterMock->setRangedToType($arg);
            $this->assertSame($arg, $abstractRangeOrSingleFilterMock->getRangedToType());
        }
    }

    public function testSetRangedToType_InvalidArgument()
    {
        $abstractRangeOrSingleFilterMock = $this->getAbstractRangeOrSingleFilterMock();

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
            true,
            false,
            AbstractRangeOrSingleFilter::RANGED_FROM_TYPE_GREATER,
            AbstractRangeOrSingleFilter::RANGED_FROM_TYPE_GREATER_OR_EQUAL,
        ];

        $exceptionCount = 0;

        foreach ($args as $arg) {
            try {
                $abstractRangeOrSingleFilterMock->setRangedToType($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetCallableValidatorConvertValue()
    {
        $mock = $this->getAbstractRangeOrSingleFilterMock();
        $result = $mock->getCallableValidatorConvertValue();
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\CallableFunction\Validator\ConvertRangedValueFunctionValidator',
            $result
        );
    }

    /**
     * Gets AbstractRangeOrSingleFilter mock object.
     *
     * @param bool|array $methods
     * @param string     $name
     *
     * @return AbstractRangeOrSingleFilter|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getAbstractRangeOrSingleFilterMock($methods = false, $name = 'name')
    {
        return $this->getAbstractFilterMock(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractRangeOrSingleFilter',
            $methods,
            [$name]
        );
    }
}
