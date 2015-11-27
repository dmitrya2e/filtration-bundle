<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Manager;

use Da2e\FiltrationBundle\Filter\Collection\CollectionInterface;
use Da2e\FiltrationBundle\Filter\Collection\Creator\CollectionCreatorInterface;
use Da2e\FiltrationBundle\Filter\Creator\FilterCreatorInterface;
use Da2e\FiltrationBundle\Filter\Executor\FilterExecutorInterface;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;
use Da2e\FiltrationBundle\Form\Creator\FormCreatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Super manager (kind of little "God" class) for all filtration components and its lifecycle.
 *
 * This manager contains too coupled and mixed functions, which is not really a fractal of good design.
 * Though it is convenient to work with this manager, because otherwise, as said earlier,
 * there are a lot of independent components, which you must use to create the "filters" and actually execute them.
 * FilterSuperManager eases this process.
 *
 * However, it is only the choice of every developer whether to use this manager or not.
 * Check the documentation to find out how to work with the filters without FilterSuperManager.
 *
 * @uses   \Da2e\FiltrationBundle\Filter\Collection\Creator\CollectionCreatorInterface::create()
 * @uses   \Da2e\FiltrationBundle\Filter\Creator\FilterCreatorInterface::create()
 * @uses   \Da2e\FiltrationBundle\Form\Creator\FormCreatorInterface::create()
 * @uses   \Da2e\FiltrationBundle\Form\Creator\FormCreatorInterface::createNamed()
 * @uses   \Da2e\FiltrationBundle\Filter\Executor\FilterExecutorInterface::execute()
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterSuperManager
{
    /**
     * Used to create a filter collection.
     *
     * @var CollectionCreatorInterface
     */
    protected $collectionCreator;

    /**
     * Used to create filters.
     *
     * @var FilterCreatorInterface
     */
    protected $filterCreator;

    /**
     * Used to create a filtration form.
     *
     * @var FormCreatorInterface
     */
    protected $formCreator;

    /**
     * Used to execute the filtration regarding filtration handler.
     *
     * @var FilterExecutorInterface
     */
    protected $filterExecutor;

    /**
     * @param CollectionCreatorInterface $collectionCreator
     * @param FilterCreatorInterface     $filterCreator
     * @param FormCreatorInterface       $formCreator
     * @param FilterExecutorInterface    $filterExecutor
     */
    public function __construct(
        CollectionCreatorInterface $collectionCreator,
        FilterCreatorInterface $filterCreator,
        FormCreatorInterface $formCreator,
        FilterExecutorInterface $filterExecutor
    ) {
        $this->collectionCreator = $collectionCreator;
        $this->filterCreator = $filterCreator;
        $this->formCreator = $formCreator;
        $this->filterExecutor = $filterExecutor;
    }

    /**
     * Creates a filter collection.
     *
     * @see \Da2e\FiltrationBundle\Filter\Collection\Creator\CollectionCreatorInterface::create() for complete
     *      documentation
     *
     * @return CollectionInterface
     */
    public function createFilterCollection()
    {
        return $this->collectionCreator->create();
    }

    /**
     * Adds a filter to the collection.
     *
     * @param CollectionInterface $collection The filter collection
     * @param string              $typeAlias  The alias of the filter service definition
     * @param string              $name       The internal name of the filter
     * @param array               $options    (Optional) The options of the filter
     *
     * @see \Da2e\FiltrationBundle\Filter\Creator\FilterCreatorInterface::create() for complete documentation
     * @see \Da2e\FiltrationBundle\Filter\Collection\CollectionInterface::addFilter() for complete documentation
     *
     * @return FilterInterface
     */
    public function addFilter(CollectionInterface $collection, $typeAlias, $name, array $options = [])
    {
        $filter = $this->filterCreator->create($typeAlias, $name, $options);
        $collection->addFilter($filter);

        return $filter;
    }

    /**
     * Creates a filtration form (unnamed).
     *
     * @param CollectionInterface $collection               The filter collection
     * @param array               $rootFormBuilderOptions   (Optional)
     * @param array               $filterFormBuilderOptions (Optional)
     * @param Request|null        $request                  (Optional) If passed Request object,
     *                                                      FormInterface::handleRequest() will be executed
     *
     * @see \Da2e\FiltrationBundle\Form\Creator\FormCreatorInterface::create() for complete documentation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(
        CollectionInterface $collection,
        array $rootFormBuilderOptions = [],
        array $filterFormBuilderOptions = [],
        Request $request = null
    ) {
        $form = $this->formCreator->create($collection, $rootFormBuilderOptions, $filterFormBuilderOptions);

        if ($request !== null) {
            $form->handleRequest($request);
        }

        return $form;
    }

    /**
     * Creates a filtration named form.
     *
     * @param string              $name                     The name of the root form
     * @param CollectionInterface $collection               The filter collection
     * @param array               $rootFormBuilderOptions   (Optional)
     * @param array               $filterFormBuilderOptions (Optional)
     * @param Request|null        $request                  (Optional) If passed Request object,
     *                                                      FormInterface::handleRequest() will be executed
     *
     * @see \Da2e\FiltrationBundle\Form\Creator\FormCreatorInterface::createNamed() for complete documentation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createNamedForm(
        $name,
        CollectionInterface $collection,
        array $rootFormBuilderOptions = [],
        array $filterFormBuilderOptions = [],
        Request $request = null
    ) {
        $form = $this->formCreator->createNamed($name, $collection, $rootFormBuilderOptions, $filterFormBuilderOptions);

        if ($request !== null) {
            $form->handleRequest($request);
        }

        return $form;
    }

    /**
     * Executes the filtration regarding filtration handlers.
     *
     * @param CollectionInterface $collection The filter collection
     * @param array               $handlers   Filtration handlers
     *
     * @see \Da2e\FiltrationBundle\Filter\Executor\FilterExecutorInterface::execute() for complete documentation
     *
     * @return FilterExecutorInterface
     */
    public function executeFilters(CollectionInterface $collection, array $handlers)
    {
        return $this->filterExecutor->execute($collection, $handlers);
    }
}
