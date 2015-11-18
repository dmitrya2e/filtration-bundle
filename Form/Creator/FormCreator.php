<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Form\Creator;

use Da2e\FiltrationBundle\Exception\Form\Creator\FormCreatorException;
use Da2e\FiltrationBundle\Filter\Collection\CollectionInterface;
use Da2e\FiltrationBundle\Filter\Filter\CustomAppendFormFieldsInterface;
use Da2e\FiltrationBundle\Filter\Filter\FilterHasFormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * The default form creator class, which is used to create a filtration form, based on the filter collection.
 * The FormCreator creates and returns standard Symfony form objects.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FormCreator implements FormCreatorInterface
{
    /**
     * Configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The form factory.
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     * @param array                $config
     */
    public function __construct(FormFactoryInterface $formFactory, array $config)
    {
        $this->validateConfig($config);

        $this->formFactory = $formFactory;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     *
     * @return FormInterface
     */
    public function create(
        CollectionInterface $filterCollection,
        array $rootFormBuilderOptions = [],
        array $filterFormBuilderOptions = []
    ) {
        return $this->createForm($filterCollection, null, $rootFormBuilderOptions, $filterFormBuilderOptions);
    }

    /**
     * {@inheritDoc}
     *
     * @return FormInterface
     */
    public function createNamed(
        $name = null,
        CollectionInterface $filterCollection,
        array $rootFormBuilderOptions = [],
        array $filterFormBuilderOptions = []
    ) {
        return $this->createForm($filterCollection, $name, $rootFormBuilderOptions, $filterFormBuilderOptions);
    }

    /**
     * Creates the filtration form.
     *
     * @param CollectionInterface $filterCollection         The collection of the filters
     * @param null|string         $name                     (Optional) The name of the root form
     * @param array               $rootFormBuilderOptions   (Optional) The options for the root form
     * @param array               $filterFormBuilderOptions (Optional) The options for each filter form
     *
     * @return FormInterface The form with one root element, which contains as many sub-forms, as many filters there
     *                       are in the collection (only considering filters, which have a form representations).
     */
    protected function createForm(
        CollectionInterface $filterCollection,
        $name = null,
        array $rootFormBuilderOptions = [],
        array $filterFormBuilderOptions = []

    ) {
        // Create the root form builder, which is used as the container for all filter forms.
        $rootFormBuilder = $this->createFormBuilder($name, 'form', null, $rootFormBuilderOptions);

        if ($filterCollection->hasFilters()) {
            foreach ($filterCollection as $filter) {
                if (!($filter instanceof FilterHasFormInterface) || $filter->hasForm() !== true) {
                    // Create a form for only filters, which have a form representation.
                    continue;
                }

                // Create the filter form builder itself.
                $filterFormBuilder = $this->createFormBuilder(
                    $filter->getName(),
                    $this->createFilterFormType(),
                    $filter,
                    array_merge($filterFormBuilderOptions, ['data_class' => get_class($filter)])
                );

                // Append the filter to the filter form.
                $this->appendFormField($filter, $filterFormBuilder);

                // Finally, add the filter form builder to the root form builder.
                $rootFormBuilder->add($filterFormBuilder);
            }
        }

        return $rootFormBuilder->getForm();
    }

    /**
     * The main "actor" here.
     * Appends a filter to the representation form.
     * If the filter has defined a custom function for form appending, than it will be used.
     *
     * @param FilterHasFormInterface $filter            The filter
     * @param FormBuilderInterface   $filterFormBuilder A form builder for a specific filter
     *
     * @return FilterHasFormInterface|CustomAppendFormFieldsInterface
     */
    protected function appendFormField(FilterHasFormInterface $filter, FormBuilderInterface $filterFormBuilder)
    {
        if (!($filter instanceof CustomAppendFormFieldsInterface)) {
            $filter->appendFormFieldsToForm($filterFormBuilder);

            return $filter;
        }

        $callableFunction = $filter->getAppendFormFieldsFunction();

        if (!is_callable($callableFunction)) {
            /** @var FilterHasFormInterface $filter */
            $filter->appendFormFieldsToForm($filterFormBuilder);

            return $filter;
        }

        call_user_func($callableFunction, $filter, $filterFormBuilder);

        return $filter;
    }

    /**
     * Creates and returns a form builder.
     *
     * @param null|string   $name    For named form builder must not be null
     * @param string|object $type    Form type
     * @param mixed|null    $data    Form data
     * @param array         $options Form builder options
     *
     * @return FormBuilderInterface
     */
    protected function createFormBuilder($name = null, $type = 'form', $data = null, array $options = [])
    {
        if ($name !== null) {
            return $this->formFactory->createNamedBuilder($name, $type, $data, $options);
        }

        return $this->formFactory->createBuilder($type, $data, $options);
    }

    /**
     * Creates filter form type object.
     *
     * @return FormTypeInterface
     */
    protected function createFilterFormType()
    {
        return new $this->config['form_filter_type_class'];
    }

    /**
     * Validates configuration.
     *
     * @param array $config
     *
     * @throws FormCreatorException
     */
    protected function validateConfig(array $config)
    {
        // For now it's only required to pass a filter form type class.
        // If you don't want to use a defined form type class,
        // you can override the FormCreator object, implement FormCreatorInterface and customize it.
        $requiredKeys = ['form_filter_type_class'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $config)) {
                throw new FormCreatorException(sprintf(
                    'Key "%s" must be presented in the filters configuration.', $key
                ));
            }

            if (!class_exists($config[$key])) {
                throw new FormCreatorException(sprintf('Class "%s" is not found.', $config[$key]));
            }
        }
    }
}
