<?php

namespace Da2e\FiltrationBundle\Tests\Form\Creator;

use Da2e\FiltrationBundle\Filter\Collection\Collection;
use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Da2e\FiltrationBundle\Form\Creator\FormCreator;
use Da2e\FiltrationBundle\Tests\TestCase;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormConfigBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormBuilderInterface;

/**
 * Class FormCreatorTest
 * @package Da2e\FiltrationBundle\Tests\Form\Creator
 */
class FormCreatorTest extends TestCase
{
    public function testConstruct()
    {
        $formFactoryMock = $this->getFormFactoryMock();
        $config = ['foo' => 'bar'];

        $formCreatorMock = $this->getFormCreatorMock(['validateConfig'], false);
        $formCreatorMock->expects($this->once())->method('validateConfig')->with($config);

        $formCreatorMock->__construct($formFactoryMock, $config);

        $configProp = $this->getPrivateProperty('Da2e\FiltrationBundle\Form\Creator\FormCreator', 'config');
        $this->assertSame($config, $configProp->getValue($formCreatorMock));

        $formFactoryProp = $this->getPrivateProperty('Da2e\FiltrationBundle\Form\Creator\FormCreator', 'formFactory');
        $this->assertSame($formFactoryMock, $formFactoryProp->getValue($formCreatorMock));
    }

    public function testCreate()
    {
        $filterCollectionMock = $this->getFilterCollectionMock();
        $opt1 = ['foo' => 'bar'];
        $opt2 = ['baz' => 'bar'];

        $formCreatorMock = $this->getFormCreatorMock(['createForm'], false);
        $formCreatorMock->expects($this->once())->method('createForm')->with($filterCollectionMock, null, $opt1, $opt2);

        $formCreatorMock->create($filterCollectionMock, $opt1, $opt2);
    }

    public function testCreateNamed()
    {
        $filterCollectionMock = $this->getFilterCollectionMock();
        $name = 'foobar';
        $opt1 = ['foo' => 'bar'];
        $opt2 = ['baz' => 'bar'];

        $formCreatorMock = $this->getFormCreatorMock(['createForm'], false);
        $formCreatorMock->expects($this->once())->method('createForm')
            ->with($filterCollectionMock, $name, $opt1, $opt2);

        $formCreatorMock->createNamed($name, $filterCollectionMock, $opt1, $opt2);
    }

    public function testValidateConfig()
    {
        $formCreatorMock = $this->getFormCreatorMock();
        $this->invokeMethod($formCreatorMock, 'validateConfig', [
            ['form_filter_type_class' => 'Da2e\FiltrationBundle\Form\Type\FilterType']
        ]);
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Form\Creator\FormCreatorException
     */
    public function testValidateConfig_NoRequiredKey()
    {
        $formCreatorMock = $this->getFormCreatorMock();
        $this->invokeMethod($formCreatorMock, 'validateConfig', [
            []
        ]);
    }

    /**
     * @expectedException \Da2e\FiltrationBundle\Exception\Form\Creator\FormCreatorException
     */
    public function testValidateConfig_ClassDoesNotExist()
    {
        $formCreatorMock = $this->getFormCreatorMock();
        $this->invokeMethod($formCreatorMock, 'validateConfig', [
            ['form_filter_type_class' => 'Da2e\FiltrationBundle\Form\Type\FilterType222']
        ]);
    }

    public function testAppendFormField()
    {
        $formCreatorMock = $this->getFormCreatorMock();
        $formBuilderMock = $this->getCustomAbstractMock('Symfony\Component\Form\FormBuilderInterface', []);

        $filterMock = $this->getCustomAbstractMock('Da2e\FiltrationBundle\Filter\Filter\FilterHasFormInterface', [
            'appendFormFieldsToForm',
        ]);

        $filterMock->expects($this->once())->method('appendFormFieldsToForm')->with($formBuilderMock);

        $result = $this->invokeMethod($formCreatorMock, 'appendFormField', [$filterMock, $formBuilderMock]);
        $this->assertSame($filterMock, $result);
    }

    public function testAppendFormField_CustomFunction()
    {
        $formCreatorMock = $this->getFormCreatorMock();
        $formBuilderMock = $this->getCustomAbstractMock('Symfony\Component\Form\FormBuilderInterface', ['foo']);
        $formBuilderMock->expects($this->once())->method('foo');

        $filterMock = $this->getCustomAbstractMock(
            'Da2e\FiltrationBundle\Filter\Filter\AbstractFilter',
            ['appendFormFieldsToForm', 'getAppendFormFieldsFunction', 'bar']
        );

        $filterMock->expects($this->never())->method('appendFormFieldsToForm');
        $filterMock->expects($this->once())->method('bar');
        $filterMock->expects($this->atLeastOnce())->method('getAppendFormFieldsFunction')->willReturn(
            function ($filter, $formBuilder) {
                $filter->bar();
                $formBuilder->foo();
            }
        );

        $result = $this->invokeMethod($formCreatorMock, 'appendFormField', [$filterMock, $formBuilderMock]);
        $this->assertSame($filterMock, $result);
    }

    public function testAppendFormField_CustomFunction_NotCallable()
    {
        $formCreatorMock = $this->getFormCreatorMock();
        $formBuilderMock = $this->getCustomAbstractMock('Symfony\Component\Form\FormBuilderInterface', []);

        $filterMock = $this->getCustomAbstractMock(
            'Da2e\FiltrationBundle\Filter\Filter\AbstractFilter',
            ['appendFormFieldsToForm', 'getAppendFormFieldsFunction',]
        );

        $filterMock->expects($this->once())->method('appendFormFieldsToForm')->with($formBuilderMock);
        $filterMock->expects($this->atLeastOnce())->method('getAppendFormFieldsFunction')->willReturn('foobar');

        $result = $this->invokeMethod($formCreatorMock, 'appendFormField', [$filterMock, $formBuilderMock]);
        $this->assertSame($filterMock, $result);
    }

    public function testCreateFormBuilder()
    {
        $formFactoryMock = $this->getFormFactoryMock(['createBuilder']);
        $formFactoryMock->expects($this->once())->method('createBuilder')
            ->with('form', null, [])
            ->willReturn('form_builder');

        $formCreatorMock = $this->getFormCreatorMock(['validateConfig'], [$formFactoryMock, []]);
        $result = $this->invokeMethod($formCreatorMock, 'createFormBuilder');

        $this->assertSame('form_builder', $result);
    }

    public function testCreateFormBuilder_WithParams()
    {
        $formFactoryMock = $this->getFormFactoryMock(['createBuilder']);
        $formFactoryMock->expects($this->once())->method('createBuilder')
            ->with('form2', 'data', ['foo'])
            ->willReturn('form_builder');

        $formCreatorMock = $this->getFormCreatorMock(['validateConfig'], [$formFactoryMock, []]);
        $result = $this->invokeMethod($formCreatorMock, 'createFormBuilder', [null, 'form2', 'data', ['foo']]);

        $this->assertSame('form_builder', $result);
    }

    public function testCreateFormBuilder_WithParams_Named()
    {
        $formFactoryMock = $this->getFormFactoryMock(['createNamedBuilder']);
        $formFactoryMock->expects($this->once())->method('createNamedBuilder')
            ->with('name', 'form2', 'data', ['foo'])
            ->willReturn('form_builder');

        $formCreatorMock = $this->getFormCreatorMock(['validateConfig'], [$formFactoryMock, []]);
        $result = $this->invokeMethod($formCreatorMock, 'createFormBuilder', ['name', 'form2', 'data', ['foo']]);

        $this->assertSame('form_builder', $result);
    }

    public function testCreateForm_NoFilters()
    {
        $formFactoryMock = $this->getFormFactoryMock();
        $formCreatorMock = $this->getFormCreatorMock(
            ['validateConfig', 'createFilterFormType', 'appendFormField', 'createFormBuilder'],
            [$formFactoryMock, []]
        );

        $filterCollection = new Collection();

        // createFilterFormType expectations
        $formCreatorMock->expects($this->never())->method('createFilterFormType');

        // createFormBuilder expectations
        // create root form builder
        $formBuilder = $this->getCustomMock('\stdClass', ['getForm']);
        $formBuilder->expects($this->atLeastOnce())->method('getForm')->willReturn('form');
        $formCreatorMock->expects($this->once())->method('createFormBuilder')->with(null, 'form', null, [])
            ->willReturn($formBuilder);

        $result = $this->invokeMethod($formCreatorMock, 'createForm', [$filterCollection]);
        $this->assertSame('form', $result);
    }

    public function testCreateForm_NoFilters_CustomParams()
    {
        $formFactoryMock = $this->getFormFactoryMock();
        $formCreatorMock = $this->getFormCreatorMock(
            ['validateConfig', 'createFilterFormType', 'appendFormField', 'createFormBuilder'],
            [$formFactoryMock, []]
        );

        $filterCollection = new Collection();

        // createFilterFormType expectations
        $formCreatorMock->expects($this->never())->method('createFilterFormType');

        // createFormBuilder expectations
        // create root form builder
        $formBuilder = $this->getCustomMock('\stdClass', ['getForm']);
        $formBuilder->expects($this->atLeastOnce())->method('getForm')->willReturn('form');
        $formCreatorMock->expects($this->once())->method('createFormBuilder')->with('name', 'form', null, ['foo'])
            ->willReturn($formBuilder);

        $result = $this->invokeMethod($formCreatorMock, 'createForm', [$filterCollection, 'name', ['foo'], ['bar']]);
        $this->assertSame('form', $result);
    }

    // [TODO]: fix this test. Mocked object \Symfony\Component\Form\FormBuilder does not implement FormBuilder interface.
    // $mockedFormBuilder instanceof FormBuilderInterface = false
//    public function testCreateForm()
//    {
//        $formFactoryMock = $this->getFormFactoryMock();
//        $formCreatorMock = $this->getFormCreatorMock(
//            ['validateConfig', 'createFilterFormType', 'appendFormField', 'createFormBuilder'],
//            [$formFactoryMock, []]
//        );
//
//        $filterClass = 'Da2e\FiltrationBundle\Filter\Filter\AbstractFilter';
//        $filterMethods = ['hasForm', 'getName'];
//
//        $filterCollection = new Collection();
//
//        // Only 2 of filters must be used in form creation.
//        $filter1 = $this->getAbstractFilterMock($filterClass, $filterMethods);
//        $filter2 = $this->getAbstractFilterMock($filterClass, $filterMethods);
//        $filter3 = $this->getAbstractFilterMock($filterClass, $filterMethods);
//
//        // This filter has not FilterHasForm interface.
//        $filter4 = $this->getAbstractFilterMock('Da2e\FiltrationBundle\Filter\Filter\FilterInterface', ['getName']);
//
//        $filter1->expects($this->atLeastOnce())->method('hasForm')->willReturn(true);
//        $filter1->expects($this->atLeastOnce())->method('getName')->willReturn('name_1');
//
//        // Has form = false
//        $filter2->expects($this->atLeastOnce())->method('hasForm')->willReturn(false);
//        $filter2->expects($this->atLeastOnce())->method('getName')->willReturn('name_2');
//
//        $filter3->expects($this->atLeastOnce())->method('hasForm')->willReturn(true);
//        $filter3->expects($this->atLeastOnce())->method('getName')->willReturn('name_3');
//
//        $filter4->expects($this->atLeastOnce())->method('getName')->willReturn('name_4');
//
//        // createFilterFormType expectations
//        $formCreatorMock->expects($this->at(0))->method('createFilterFormType')->willReturn('filter_form_type_1');
//        $formCreatorMock->expects($this->at(1))->method('createFilterFormType')->willReturn('filter_form_type_3');
//        $formCreatorMock->expects($this->exactly(2))->method('createFilterFormType');
//
//        // createFormBuilder expectations
//        $filterFormBuilder1 = $this->getCustomMock('\Symfony\Component\Form\FormBuilder');
//        $filterFormBuilder3 = $this->getCustomMock('\Symfony\Component\Form\FormBuilder');
//
//        // 0 - create root form builder
//        $rootFormBuilder = $this->getCustomAbstractMock('\Symfony\Component\Form\FormBuilderInterface', [
//            'add',
//            'getForm'
//        ]);
//
//        $rootFormBuilder->expects($this->at(0))->method('add')->with($filterFormBuilder1);
//        $rootFormBuilder->expects($this->at(1))->method('add')->with($filterFormBuilder3);
//        $rootFormBuilder->expects($this->exactly(2))->method('add');
//        $rootFormBuilder->expects($this->atLeastOnce())->method('getForm')->willReturn('form');
//
//        $formCreatorMock->expects($this->at(0))->method('createFormBuilder')
//            ->with(null, 'form', null, [])
//            ->willReturn($rootFormBuilder);
//
//        // 1 and 2 - create form builders for each filter with form
//        $formCreatorMock->expects($this->at(1))->method('createFormBuilder')
//            ->with('name_1', 'filter_form_type_1', $filter1, [
//                'data_class' => get_class($filter1),
//            ])
//            ->willReturn($filterFormBuilder1);
//
//        $formCreatorMock->expects($this->at(2))->method('createFormBuilder')
//            ->with('name_3', 'filter_form_type_3', $filter3, [
//                'data_class' => get_class($filter3),
//            ])
//            ->willReturn($filterFormBuilder3);
//
//        $formCreatorMock->expects($this->exactly(3))->method('createFormBuilder');
//
//        // appendFormField expectations
//        $formCreatorMock->expects($this->at(0))->method('appendFormField')->with($filter1, $filterFormBuilder1);
//        $formCreatorMock->expects($this->at(1))->method('appendFormField')->with($filter3, $filterFormBuilder3);
//        $formCreatorMock->expects($this->exactly(2))->method('appendFormField');
//
//        $filterCollection->addFilter($filter1);
//        $filterCollection->addFilter($filter2);
//        $filterCollection->addFilter($filter3);
//        $filterCollection->addFilter($filter4);
//
//        $result = $this->invokeMethod($formCreatorMock, 'createForm', [$filterCollection]);
//        $this->assertSame('form', $result);
//    }

    // [TODO]: copy testCreateForm() and check it with custom params (testCreateForm_CustomParams()).

    public function testCreateFilterFormType()
    {
        $formFactoryMock = $this->getFormFactoryMock();
        $formCreatorMock = $this->getFormCreatorMock(null, [$formFactoryMock, [
            'form_filter_type_class' => '\stdClass',
        ]]);

        $result = $this->invokeMethod($formCreatorMock, 'createFilterFormType');
        $this->assertInstanceOf('\stdClass', $result);
    }

    /**
     * Gets FormFactory mock.
     *
     * @param bool|array|null $methods
     * @param bool|array|null $constructorArgs
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|FormFactoryInterface
     */
    private function getFormFactoryMock($methods = [], $constructorArgs = false)
    {
        return $this->getCustomAbstractMock('Symfony\Component\Form\FormFactoryInterface', $methods, $constructorArgs);
    }

    /**
     * Gets FormCreator mock.
     *
     * @param bool|array|null $methods
     * @param bool|array|null $constructorArgs
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|FormCreator
     */
    private function getFormCreatorMock($methods = null, $constructorArgs = false)
    {
        return $this->getCustomMock('Da2e\FiltrationBundle\Form\Creator\FormCreator', $methods, $constructorArgs);
    }

    /**
     * Gets FilterCollection mock.
     *
     * @param bool|array|null $methods
     * @param bool|array|null $constructorArgs
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Collection
     */
    private function getFilterCollectionMock($methods = [], $constructorArgs = false)
    {
        return $this->getCustomAbstractMock(
            'Da2e\FiltrationBundle\Filter\Collection\CollectionInterface',
            $methods,
            $constructorArgs
        );
    }

    /**
     * Gets abstract filter mock.
     *
     * @param string          $className
     * @param bool|array|null $methods         False for no method mocking
     * @param bool|array      $constructorArgs False to disable original constructor
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractFilter
     */
    protected function getAbstractFilterMock($className, $methods = null, $constructorArgs = ['name'])
    {
        return $this->getCustomAbstractMock($className, $methods, $constructorArgs);
    }
}
