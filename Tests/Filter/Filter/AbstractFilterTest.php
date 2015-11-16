<?php

namespace Da2e\FiltrationBundle\Tests\Filter\Filter;

use Da2e\FiltrationBundle\CallableFunction\Validator\CallableFunctionValidatorInterface;
use Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AbstractFilterTest
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter
 */
class AbstractFilterTest extends AbstractFilterTestCase
{
    public function testConstruct()
    {
        $abstractFilterMock = $this->getAbstractFilterMock(false, 'name');
        $this->assertSame('name', $abstractFilterMock->getName());
    }

    public function testConstruct_DefaultValues()
    {
        $abstractFilterMock = $this->getAbstractFilterMock(false, 'name');
        $this->assertSame('name', $abstractFilterMock->getName());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException
     */
    public function testConstruct_InvalidName()
    {
        $this->getAbstractFilterMock(false, null);
    }

    public function testGetValidOptions()
    {
        $this->assertTrue(is_array(AbstractFilter::getValidOptions()));
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException
     */
    public function testAppendFormFieldsToForm()
    {
        $this->invokeMethod($this->getAbstractFilterMock(), 'appendFormFieldsToForm', [$this->getFormBuilderMock()]);
    }

    public function testHasAppliedValue()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertFalse($abstractFilterMock->hasAppliedValue());
    }

    public function testHasAppliedValue_True()
    {
        $abstractFilterMock = $this->getAbstractFilterMock(['getConvertedValue']);
        $abstractFilterMock->expects($this->at(0))->method('getConvertedValue')->willReturn(['foo', 'bar']);
        $abstractFilterMock->expects($this->at(1))->method('getConvertedValue')->willReturn('foobar');
        $abstractFilterMock->expects($this->at(2))->method('getConvertedValue')->willReturn(1);
        $abstractFilterMock->expects($this->at(3))->method('getConvertedValue')->willReturn(1.0);
        $abstractFilterMock->expects($this->at(4))->method('getConvertedValue')->willReturn(new \stdClass());

        $this->assertTrue($abstractFilterMock->hasAppliedValue());
        $this->assertTrue($abstractFilterMock->hasAppliedValue());
        $this->assertTrue($abstractFilterMock->hasAppliedValue());
        $this->assertTrue($abstractFilterMock->hasAppliedValue());
        $this->assertTrue($abstractFilterMock->hasAppliedValue());
    }

    public function testHasAppliedValue_False()
    {
        $abstractFilterMock = $this->getAbstractFilterMock(['getConvertedValue']);
        $abstractFilterMock->expects($this->at(0))->method('getConvertedValue')->willReturn([]);
        $abstractFilterMock->expects($this->at(1))->method('getConvertedValue')->willReturn('');
        $abstractFilterMock->expects($this->at(2))->method('getConvertedValue')->willReturn(0);
        $abstractFilterMock->expects($this->at(3))->method('getConvertedValue')->willReturn(0.0);

        $this->assertFalse($abstractFilterMock->hasAppliedValue());
        $this->assertFalse($abstractFilterMock->hasAppliedValue());
        $this->assertFalse($abstractFilterMock->hasAppliedValue());
        $this->assertFalse($abstractFilterMock->hasAppliedValue());
    }

    public function testHasAppliedValue_CustomFunction_True()
    {
        $abstractFilterMock = $this->getAbstractFilterMock(['getConvertedValue']);
        $abstractFilterMock->setHasAppliedValueFunction(function(FilterInterface $filter) {
            return $filter->getConvertedValue() === 'foobar';
        });

        $abstractFilterMock->expects($this->once())->method('getConvertedValue')->willReturn('barfoo');
        $this->assertFalse($abstractFilterMock->hasAppliedValue());
    }

    public function testHasAppliedValue_CustomFunction_False()
    {
        $abstractFilterMock = $this->getAbstractFilterMock(['getConvertedValue']);
        $abstractFilterMock->setHasAppliedValueFunction(function(FilterInterface $filter) {
            return $filter->getConvertedValue() === 'foobar';
        });

        $abstractFilterMock->expects($this->once())->method('getConvertedValue')->willReturn('foobar');
        $this->assertTrue($abstractFilterMock->hasAppliedValue());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Filter\FilterException
     */
    public function testHasAppliedValue_CustomFunction_ExceptionOnInvalidReturnValue()
    {
        $abstractFilterMock = $this->getAbstractFilterMock(['getConvertedValue']);
        $abstractFilterMock->setHasAppliedValueFunction(function(FilterInterface $filter) {
            return 'foo';
        });

        $abstractFilterMock->hasAppliedValue();
    }

    public function testGetConvertedValue()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertNull($abstractFilterMock->getConvertedValue());
    }

    public function testGetConvertedValue_Converted()
    {
        $abstractFilterMock = $this->getAbstractFilterMock(['convertValue']);
        $abstractFilterMock->expects($this->once())->method('convertValue')->willReturn('foobar');

        $this->assertSame('foobar', $abstractFilterMock->getConvertedValue());
        $this->assertSame('foobar', $abstractFilterMock->getConvertedValue());
    }

    public function testGetName()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertSame('name', $abstractFilterMock->getName());
    }

    public function testSetName()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $abstractFilterMock->setName('foobar');
        $this->assertSame('foobar', $abstractFilterMock->getName());
    }

    public function testSetName_InvalidArg()
    {
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
            true,
            false,
        ];

        $exceptionCount = 0;

        foreach ($args as $arg) {
            $abstractFilterMock = $this->getAbstractFilterMock();

            try {
                $abstractFilterMock->setName($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetFieldName()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertSame('', $abstractFilterMock->getFieldName());
    }

    public function testSetFieldName()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $abstractFilterMock->setFieldName('foobar');
        $this->assertSame('foobar', $abstractFilterMock->getFieldName());
    }

    public function testSetFieldName_InvalidArg()
    {
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
            true,
            false,
        ];

        $exceptionCount = 0;

        foreach ($args as $arg) {
            $abstractFilterMock = $this->getAbstractFilterMock();

            try {
                $abstractFilterMock->setFieldName($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetTitle()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertSame('', $abstractFilterMock->getTitle());
    }

    public function testSetTitle()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();

        $abstractFilterMock->setTitle('foobar');
        $this->assertSame('foobar', $abstractFilterMock->getTitle());

        $abstractFilterMock->setTitle('');
        $this->assertSame('', $abstractFilterMock->getTitle());
    }

    public function testSetTitle_InvalidArg()
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
        ];

        $exceptionCount = 0;

        foreach ($args as $arg) {
            $abstractFilterMock = $this->getAbstractFilterMock();

            try {
                $abstractFilterMock->setTitle($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetFormOptions()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertSame([], $abstractFilterMock->getFormOptions());
    }

    public function testSetFormOptions()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();

        $abstractFilterMock->setFormOptions(['foo', 'bar']);
        $this->assertSame(['foo', 'bar'], $abstractFilterMock->getFormOptions());

        $abstractFilterMock->setFormOptions([]);
        $this->assertSame([], $abstractFilterMock->getFormOptions());
    }

    public function testGetTransformValuesFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertNull($abstractFilterMock->getTransformValuesFunction());
    }

    public function testSetTransformValuesFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $function = function (FilterInterface $abstractFilterMock) {
        };

        $abstractFilterMock->setTransformValuesFunction($function);

        $this->assertSame($function, $abstractFilterMock->getTransformValuesFunction());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException
     */
    public function testSetTransformValuesFunction_InvalidFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $function = function () {
        };

        $abstractFilterMock->setTransformValuesFunction($function);
    }

    public function testGetHasAppliedValueFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertNull($abstractFilterMock->getHasAppliedValueFunction());
    }

    public function testHasAppliedValueFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $function = function (FilterInterface $abstractFilterMock) {
        };

        $abstractFilterMock->setHasAppliedValueFunction($function);

        $this->assertSame($function, $abstractFilterMock->getHasAppliedValueFunction());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException
     */
    public function testHasAppliedValueFunction_InvalidFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $function = function () {
        };

        $abstractFilterMock->setHasAppliedValueFunction($function);
    }

    public function testGetApplyFilterFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertNull($abstractFilterMock->getApplyFilterFunction());
    }

    public function testSetApplyFilterFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $function = function (FilterInterface $abstractFilterMock, $handler) {
        };

        $abstractFilterMock->setApplyFilterFunction($function);

        $this->assertSame($function, $abstractFilterMock->getApplyFilterFunction());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException
     */
    public function testSetApplyFilterFunction_InvalidFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $function = function () {
        };

        $abstractFilterMock->setApplyFilterFunction($function);
    }

    public function testGetAppendFormFieldsFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertNull($abstractFilterMock->getAppendFormFieldsFunction());
    }

    public function testSetAppendFormFieldsFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $function = function (FilterInterface $abstractFilterMock, FormBuilderInterface $formBuilder) {
        };

        $abstractFilterMock->setAppendFormFieldsFunction($function);

        $this->assertSame($function, $abstractFilterMock->getAppendFormFieldsFunction());
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException
     */
    public function testSetAppendFormFieldsFunction_InvalidFunction()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $function = function () {
        };

        $abstractFilterMock->setAppendFormFieldsFunction($function);
    }

    public function testHasForm()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertTrue(is_bool($abstractFilterMock->hasForm()));
    }

    public function testSetHasForm()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();

        $abstractFilterMock->setHasForm(true);
        $this->assertTrue($abstractFilterMock->hasForm());

        $abstractFilterMock->setHasForm(false);
        $this->assertFalse($abstractFilterMock->hasForm());
    }

    public function testSetHasForm_InvalidArg()
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
            ''
        ];

        $exceptionCount = 0;

        foreach ($args as $arg) {
            $abstractFilterMock = $this->getAbstractFilterMock();

            try {
                $abstractFilterMock->setHasForm($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetValue()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertNull($abstractFilterMock->getValue());
    }

    public function testSetValue()
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
            'foo',
        ];

        $abstractFilterMock = $this->getAbstractFilterMock();

        foreach ($args as $arg) {
            $abstractFilterMock->setValue($arg);
            $this->assertSame($arg, $abstractFilterMock->getValue());
        }
    }

    public function testGetDefaultValue()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertNull($abstractFilterMock->getDefaultValue());
    }

    public function testSetDefaultValue()
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
            'foo',
        ];

        $abstractFilterMock = $this->getAbstractFilterMock();

        foreach ($args as $arg) {
            $abstractFilterMock->setDefaultValue($arg);
            $this->assertSame($arg, $abstractFilterMock->getDefaultValue());
        }
    }

    public function testGetValuePropertyName()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertSame('value', $abstractFilterMock->getValuePropertyName());
    }

    public function testSetValuePropertyName()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();

        $abstractFilterMock->setValuePropertyName('value');
        $this->assertSame('value', $abstractFilterMock->getValuePropertyName());
    }

    public function testSetValuePropertyName_InvalidArg_NotString()
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

        foreach ($args as $arg) {
            $abstractFilterMock = $this->getAbstractFilterMock();

            try {
                $abstractFilterMock->setValuePropertyName($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Filter\Filter\InvalidArgumentException
     */
    public function testSetValuePropertyName_InvalidArg_NonExistantProperty()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $abstractFilterMock->setValuePropertyName('foobar');
    }

    public function testGetFormFieldType()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $this->assertSame('', $abstractFilterMock->getFormFieldType());
    }

    public function testSetFormFieldType()
    {
        $abstractFilterMock = $this->getAbstractFilterMock();
        $abstractFilterMock->setFormFieldType('foobar');
        $this->assertSame('foobar', $abstractFilterMock->getFormFieldType());
    }

    public function testSetFormFieldType_InvalidArg()
    {
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
            true,
            false,
        ];

        $exceptionCount = 0;

        foreach ($args as $arg) {
            $abstractFilterMock = $this->getAbstractFilterMock();

            try {
                $abstractFilterMock->setFormFieldType($arg);
            } catch (InvalidArgumentException $e) {
                $exceptionCount++;
            }
        }

        $this->assertEquals(count($args), $exceptionCount);
    }

    public function testGetCallableValidatorAppendFormFields()
    {
        $mock = $this->getAbstractFilterMock();
        $result = $mock->getCallableValidatorAppendFormFields();
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\CallableFunction\Validator\AppendFormFieldsFunctionValidator',
            $result
        );
    }

    public function testSetCallableValidatorAppendFormFields()
    {
        $validator = new CallableFunctionValidatorMock();

        $mock = $this->getAbstractFilterMock();
        $mock->setCallableValidatorAppendFormFields($validator);

        $result = $mock->getCallableValidatorAppendFormFields();
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Tests\Filter\Filter\CallableFunctionValidatorMock',
            $result
        );
    }

    public function testGetCallableValidatorApplyFilters()
    {
        $mock = $this->getAbstractFilterMock();
        $result = $mock->getCallableValidatorApplyFilters();
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\CallableFunction\Validator\ApplyFiltersFunctionValidator',
            $result
        );
    }

    public function testSetCallableValidatorApplyFilters()
    {
        $validator = new CallableFunctionValidatorMock();

        $mock = $this->getAbstractFilterMock();
        $mock->setCallableValidatorApplyFilters($validator);

        $result = $mock->getCallableValidatorApplyFilters();
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Tests\Filter\Filter\CallableFunctionValidatorMock',
            $result
        );
    }

    public function testGetCallableValidatorTransformValues()
    {
        $mock = $this->getAbstractFilterMock();
        $result = $mock->getCallableValidatorTransformValues();
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\CallableFunction\Validator\TransformValuesFunctionValidator',
            $result
        );
    }

    public function testSetCallableValidatorTransformValues()
    {
        $validator = new CallableFunctionValidatorMock();

        $mock = $this->getAbstractFilterMock();
        $mock->setCallableValidatorTransformValues($validator);

        $result = $mock->getCallableValidatorTransformValues();
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Tests\Filter\Filter\CallableFunctionValidatorMock',
            $result
        );
    }

    public function testGetCallableValidatorHasAppliedValue()
    {
        $mock = $this->getAbstractFilterMock();
        $result = $mock->getCallableValidatorHasAppliedValue();
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\CallableFunction\Validator\HasAppliedValueFunctionValidator',
            $result
        );
    }

    public function testSetCallableValidatorHasAppliedValue()
    {
        $validator = new CallableFunctionValidatorMock();

        $mock = $this->getAbstractFilterMock();
        $mock->setCallableValidatorHasAppliedValue($validator);

        $result = $mock->getCallableValidatorHasAppliedValue();
        $this->assertInstanceOf(
            '\Da2e\FiltrationBundle\Tests\Filter\Filter\CallableFunctionValidatorMock',
            $result
        );
    }

    /**
     * Gets abstract filter mock.
     *
     * @param bool|array|null $methods False for no method mocking
     * @param string          $name    The name of the filter
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractFilter
     */
    protected function getAbstractFilterMock($methods = false, $name = 'name')
    {
        return parent::getAbstractFilterMock(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter',
            $methods,
            [$name]
        );
    }
}

/**
 * Class CallableFunctionValidatorMock
 * @package Da2e\FiltrationBundle\Tests\Filter\Filter
 */
class CallableFunctionValidatorMock implements CallableFunctionValidatorInterface
{
    public function isValid()
    {

    }

    public function setCallableFunction(callable $callableFunction)
    {

    }
}
