# Create custom filter

[Return to the documentation index page](index.md)

## Create filter with implementation of FilterInterface

There is only one essential requirement to create a filter - to implement FilterInterface. Lets try to create a sample filter.

```php
<?php
// YourSampleFilter.php

namespace YourApp;

use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

class YourSampleFilter implements FilterInterface
{
    // Filter name
    protected $name;

    // Field name (for filtration handler and a form)
    protected $fieldName;

    // Property for keeping the value
    protected $value;

    // FilterInterface methods.
    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setFieldName($name)
    {
        $this->fieldName = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return 'your_filtration_handler_type';
    }

    /**
     * {@inheritDoc}
     */
    public function applyFilter($handler)
    {
        if ($this->value) {
            $handler
                ->andWhere(sprintf('%s = :value', $this->fieldName))
                ->setParameter('value', $this->value);
        }
    }
}
```

Note, that **getType** method returns a string "your_filtration_handler_type". This method must return a name of the filtration handler (Doctrine ORM/ODM, Sphinx, ...). FiltrationBundle is not packaged with any default filtration handler, they are provided by filtration handler adapter bundles. So we must register this handler in a configuration:

```yaml
# app/config/config.yml

da2e_filtration:
    handlers:
        your_filtration_handler_type:
            name: your_filtration_handler_type
            class: \Doctrine\ORM\QueryBuilder
```

That's all, now your filter can be used by FiltrationBundle:

```php
$collectionCreator = $serviceContainer->get('da2e.filtration.filter.collection.creator.collection_creator');
$collection = $collectionCreator->create();

$filter = new YourApp\YourSampleFilter();
$filter->setFieldName('e.foobar');

$collection->addFilter($filter);

$queryBuilder = $serviceContainer->get('doctrine.orm.entity_manager')
    ->getRepository('YourApp:YourEntity')
    ->createQueryBuilder('e');

$filterExecutor = $serviceContainer->get('da2e.filtration.filter.executor.filter_executor');
$filterExecutor->execute($collection, ['your_filtration_handler_type' => $queryBuilder]);
```

## Add some value to the filter

Of course, the filter will ne be executed, because it does not have any value. To fix it, just add a few more lines of code to sample filter:

```php
// YourSampleFilter.php

// ...

class YourSampleFilter implements FilterInterface
{
    // ...

    protected $value;

    public function setValue($value)
    {
        $this->value = $value;
    }
}
```

Now you can set a value and the filter will be executed:

```php
// ...
// assume, that filter is already in a $collection
$filter->setValue('foobar');

// ...
$filterExecutor->execute($collection, ['your_filtration_handler_type' => $queryBuilder]);
```

Now, it will be executed, since it has applied value.

## Register your filter as a service

For more convenient and reusable way of creating filter you can register it as a service:

```yaml
# app/config/services.yml

services:
    your_sample_filter:
        class: YourApp\YourSampleFilter
        tags:
            - { name:  da2e.filtration.filter, alias: your_app_sample_filter }
```

Now you can use FiltrationBundle filter creator component to create the filter:

```php
$filterCreator = $serviceContainer->get('da2e.filtration.filter.creator.filter_creator');
$filter = $filterCreator->create('your_app_sample_filter', 'sample_filter');

// Note, that you are passing filter service alias, instead of full service name.
// Second argument is the name of the filter ($filter->getName() == 'sample_filter').

// ...
// add to collection and execute via filter executor
```

## Create filter with options handling

To benefit from filter option handler, your sample filter must implement another interface **FilterOptionInterface**.

```php
// YourSampleFilter.php

// ...
use Da2e\FiltrationBundle\Filter\Filter\FilterOptionInterface;

class YourSampleFilter implements FilterInterface, FilterOptionInterface
{
    // ...

    // FilterOptionInterface requires only one method to be implemented
    public static function getValidOptions()
    {
        return [
            'field_name' => [
                'setter' => 'setFieldName',
                'type'   => 'string',
                'empty'  => false,
            ],
        ];
    }
}
```

Your sample filter now can handle one option - **field_name**. The value for it must be a string, and it cannot be empty. It is valid from now to use an array of options while creating filter via filter creator component.

```php
$filterCreator = $serviceContainer->get('da2e.filtration.filter.creator.filter_creator');
$filter = $filterCreator->create('your_app_sample_filter', 'sample_filter', [
    'field_name' => 'e.foobar',
]);

// ...
// add to collection and execute via filter executor
```

In this manner you can add as many available options as you wish. You already have a filter, which can be created with options passed by filter creator component.

## Add form representation

Most likely, you want to show your sample filter to the user in your UI via form. You can do this by following several steps. First, your sample filter must implement **FilterHasFormInterface**.

```php
// YourSampleFilter.php

// ...
use Da2e\FiltrationBundle\Filter\Filter\FilterHasFormInterface;
use Symfony\Component\Form\FormBuilderInterface;

class YourSampleFilter implements FilterInterface, FilterOptionInterface, FilterHasFormInterface
{
    // ...

    // FilterHasFormInterface methods

    // Defines if the filter actually needs a form representation
    public function hasForm()
    {
        return true;
    }

    // This method does 99% of the work
    public function appendFormFieldsToForm(FormBuilderInterface $formBuilder)
    {
        $formBuilder->add($this->name, 'text', [
            'required' => false,
        ]);
    }
}
```

Now, you can use this filter in a form via form creator component:

```php
public function yourAction(Request $request)
{
    $filterCreator = $serviceContainer->get('da2e.filtration.filter.creator.filter_creator');
    $filter = $filterCreator->create('your_app_sample_filter', 'sample_filter', [
        'field_name' => 'e.foobar',
    ]);

    $collectionCreator = $serviceContainer->get('da2e.filtration.filter.collection.creator.collection_creator');
    $collection = $collectionCreator->create();
    $collection->addFilter($filter);

    $formCreator = $serviceContainer->get('da2e.filtration.form.creator.form_creator');
    $form = $formCreator->createNamed($collection, 'filters');
    $form->handleRequest($request);

    $queryBuilder = $serviceContainer->get('doctrine.orm.entity_manager')
        ->getRepository('YourApp:YourEntity')
        ->createQueryBuilder('e');

    $filterExecutor = $serviceContainer->get('da2e.filtration.filter.executor.filter_executor');
    $filterExecutor->execute($collection, ['your_filtration_handler_type' => $queryBuilder]);

    // Assume, that your actions uses annotation @Template.
    // Pass a form view object to the template:
    return ['form' => $form->createView()];
}
```

The template might look like this:

```twig
<form action="/" method="GET">
    {{ form_label(form.filters.sample_filter) }}
    {{ form_widget(form.filters.sample_filter) }}
    <input type="submit"/>
</form>
```

As you see, managing and handling form via FiltrationBundle is very easy and straightforward as in its core it uses Symfony Form component on 100%.

As you created a named form and gave it name "filters", you can get it in a template as **form.filters**. This object is a form collection, which contains all filters, which have form representation, in this case only one filter with name "sample_filter". You can get it in a template as **form.filters.sample_filter**.

## Use AbstractFilter

You've created quite functioning filter, which can even handle a form. But that's not all. It is recommended to extend **AbstractFilter**, which already has implemented most of the code. It also offers more features, like custom implementation of some crucial methods, and more options, e.g. "default value", etc.

Lets try to adapt your sample filter in a way to use AbstractFilter:

```php
// YourSampleFilter.php

namespace YourApp;

use Da2e\FiltrationBundle\Filter\Filter\AbstractFilter;
use Symfony\Component\Form\FormBuilderInterface;

class YourSampleFilter extends AbstractFilter
{
    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return 'your_filtration_handler_type';
    }

    /**
     * {@inheritDoc}
     */
    public function applyFilter($handler)
    {
        if ($this->value) {
            $handler
                ->andWhere(sprintf('%s = :value', $this->fieldName))
                ->setParameter('value', $this->value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function appendFormFieldsToForm(FormBuilderInterface $formBuilder)
    {
        $formBuilder->add($this->name, 'text', [
            'required' => false,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function convertValue()
    {
        $value = trim(strtolower((string) $this->value));

        if ($value === '') {
            return null;
        }

        return $value;
    }
}
```

1. We have extended AbstractFilter class.
2. We have removed all properties, setters/getters and interfaces, because AbstractFilter already has all of them implemented.
3. We have left only crucial methods, which we had before: getType(), applyFilter(), appendFormFieldsToForm().
4. There is a new method **convertValue**, which is required by AbstractFilter. It simply converts a raw value to a "workable" form. In this case, we have casted the raw value to a string, strlowered and trimmed it, and checked if it is not empty. And if it is not empty, returning it, otherwise returning null.

Now you have a reduced version of your previous sample filter with benefit of AbstractFilter, which offers a lot more functionality, than you had before. You can use this filter the same way as before (via filter creator/executor, etc). [Read more here about AbstractFilter](filters-overview.md). 
