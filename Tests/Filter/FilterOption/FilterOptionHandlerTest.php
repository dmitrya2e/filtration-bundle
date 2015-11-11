<?php

namespace Da2e\FiltrationBundle\Tests\Filter\FilterOption;

use Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionException;
use Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionValidatorException;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Filter\FilterOption\FilterOptionHandler;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterOptionHandlerTest
 * @package Da2e\FiltrationBundle\Tests\Filter\FilterOption
 */
class FilterOptionHandlerTest extends TestCase
{
    public function testHandle()
    {
        $filter1 = $this->getFilterMock();

        $filterOptionHandler = new FilterOptionHandler();
        $filterOptionHandler->handle($filter1, ['field_name' => 'foobar']);

        $this->assertSame('foobar', $filter1->getFieldName());
    }

    public function testHandle_NoOptions()
    {
        $filter1 = $this->getFilterMock();
        $fieldName = $filter1->getFieldName();

        $filterOptionHandler = new FilterOptionHandler();
        $filterOptionHandler->handle($filter1, []);

        $this->assertSame($fieldName, $filter1->getFieldName());
    }

    public function testHandle_Mock()
    {
        $filter1 = $this->getFilterMock();
        $filterOptionHandler = $this->getCustomMock(
            '\Da2e\FiltrationBundle\Filter\FilterOption\FilterOptionHandler',
            ['validateFilterOptions']
        );

        $filterOptionHandler->expects($this->atLeastOnce())->method('validateFilterOptions')->with(
            ['field_name' => 'foobar'],
            $filter1::getValidOptions()
        );

        $filterOptionHandler->handle($filter1, ['field_name' => 'foobar']);
    }

    public function testValidateFilterOptions()
    {
        $filterOptionHandler = new FilterOptionHandler();
        $this->invokeMethod($filterOptionHandler, 'validateFilterOptions', [
            [
                'foo1' => 'value',
                'foo2' => 1,
                'foo3' => 'value',
                'foo4' => '',
                'foo5' => new \stdClass(),
                'foo6' => new \stdClass(),
            ],
            [
                'foo1' => [
                    'type' => 'string',
                ],
                'foo2' => [
                    'type' => ['string', 'int'],
                ],
                'foo3' => [
                    'type'  => ['string', 'int'],
                    'empty' => false,
                ],
                'foo4' => [
                    'type'  => 'string',
                    'empty' => true,
                ],
                'foo5' => [
                    'type' => 'object',
                ],
                'foo6' => [
                    'type'        => 'object',
                    'instance_of' => '\stdClass',
                ],
                'foo7' => [],
            ]
        ]);
    }

    public function testValidateFilterOptions_InvalidOptions_UnexpectedErrors()
    {
        $filterOptionHandler = new FilterOptionHandler();
        $assertions = ['', [], 'foobar'];
        $exceptionCount = 0;

        foreach ($assertions as $assert) {
            try {
                $this->invokeMethod($filterOptionHandler, 'validateFilterOptions', [
                    ['foo' => 'value'],
                    ['foo' => ['type' => $assert]]
                ]);
            } catch (FilterOptionValidatorException $e) {
                $exceptionCount--;
            } catch (FilterOptionException $e) {
                $exceptionCount++;
            }
        }

        $this->assertSame($exceptionCount, count($assertions));
    }

    public function testValidateFilterOptions_InvalidOptions_ValidationErrors()
    {
        $filterOptionHandler = new FilterOptionHandler();

        $assertions = [
            [
                ['foo' => 'value'],
                [],
            ],
            [
                ['foo' => 'value'],
                ['foo' => ['type' => 'int']],
            ],
            [
                ['foo' => new \stdClass()],
                ['foo' => ['type' => 'object', 'instance_of' => 'Foobar']],
            ],
            [
                ['foo' => ''],
                ['foo' => ['empty' => false]],
            ],
        ];

        $exceptionCount = 0;

        foreach ($assertions as $assert) {
            try {
                $this->invokeMethod($filterOptionHandler, 'validateFilterOptions', [
                    $assert[0],
                    $assert[1]
                ]);
            } catch (FilterOptionValidatorException $e) {
                $exceptionCount++;
            }
        }

        $this->assertSame($exceptionCount, count($assertions));
    }

    public function testSetFilterOptions()
    {
        $filterOptionHandler = new FilterOptionHandler();

        $this->invokeMethod($filterOptionHandler, 'setFilterOptions', [
            $filter1 = $this->getFilterMock(),
            ['field_name' => 'foo', 'name' => 'bar'],
            [
                'field_name' => ['setter' => 'setFieldName', 'type' => 'string'],
                'name'       => ['setter' => 'setName', 'type' => 'string'],
            ]
        ]);

        $this->assertSame('foo', $filter1->getFieldName());
        $this->assertSame('bar', $filter1->getName());
    }

    public function testSetFilterOptions_UnexpectedErrors()
    {
        $filterOptionHandler = new FilterOptionHandler();

        $assertions = [
            [
                ['field_name' => 'foo'],
                ['field_name' => ['setter' => 'setFoobar123']],
            ],
            [
                ['field_name' => 'foo'],
                ['field_name' => []],
            ],
        ];


        $exceptionCount = 0;

        foreach ($assertions as $assert) {
            try {
                $this->invokeMethod($filterOptionHandler, 'setFilterOptions', [
                    $this->getFilterMock(),
                    $assert[0],
                    $assert[1]
                ]);
            } catch (FilterOptionException $e) {
                $exceptionCount++;
            }
        }

        $this->assertSame($exceptionCount, count($assertions));
    }

    /**
     * @param array|null|false $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractFilter
     */
    private function getFilterMock($methods = [])
    {
        return $this->getCustomAbstractMock(
            '\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter',
            $methods,
            ['name']
        );
    }
}
