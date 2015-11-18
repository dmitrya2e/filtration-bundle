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

use Da2e\FiltrationBundle\Filter\Collection\CollectionInterface;
use Symfony\Component\Form\FormInterface;

/**
 * An interface for a form creator class.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
interface FormCreatorInterface
{
    /**
     * Creates form (unnamed).
     *
     * @param CollectionInterface $filterCollection         The collection of the filters
     * @param array               $rootFormBuilderOptions   (Optional) The options for the root form (parent container for each filter sub-forms)
     * @param array               $filterFormBuilderOptions (Optional) The options for each filter form
     *
     * @return FormInterface|mixed
     */
    public function create(
        CollectionInterface $filterCollection,
        array $rootFormBuilderOptions = [],
        array $filterFormBuilderOptions = []
    );

    /**
     * Creates named form.
     *
     * @param string              $name                     The name of the root form
     * @param CollectionInterface $filterCollection         The collection of the filters
     * @param array               $rootFormBuilderOptions   (Optional) The options for the root form (parent container for each filter sub-forms)
     * @param array               $filterFormBuilderOptions (Optional) The options for each filter form
     *
     * @return FormInterface|mixed
     */
    public function createNamed(
        $name,
        CollectionInterface $filterCollection,
        array $rootFormBuilderOptions = [],
        array $filterFormBuilderOptions = []
    );
}
