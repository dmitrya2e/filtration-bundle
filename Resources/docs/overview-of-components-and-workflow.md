# Overview of components and workflow

[Return to the documentation index page](index.md)

## FiltrationBundle and filtration handler adapter bundles

FiltrationBundle itself provide only a convenient base for further filtration handling. **It is not packaged with a final implementations of filters**, instead it offers a set of standard filter abstractions, which may be used for building a final implementation of filters for a specific handler/provider (Doctrine ORM/ODM, Sphinx, ...).

However, there are available ready-to-use implementations of filtration handlers. You can find them on [the index page](../../../).

## Introduction

Whole workflow of the filtration bundle consist of several components:
- Abstract filter object
- Filter creator
- Filter option handler
- Filter collection creator
- Filter collection
- Filter executor
- Form creator

However, it is not necessary to use exactly these components (which would be strange, because the bundle provides them ready and for specific purposes).

You can replace any of them with your own implementations or you can use only concrete filter objects (which are based on abstract filter object) without any of other components, or even use FilterInterface only to build your own custom filter.

## Components overview

### Abstract filter

Abstract filter is a base filter class, which may be extended by filters.

This class provides maximum capabilities FiltrationBundle can offer:
- Option setting via filter option handler component (implemented FilterOptionInterface)
- Form handling (implemented FilterHasFormInterface)
- ... and much more. Learn more about abstract filter [here](filters-reference-abstract-filter.md).

Core abstract methods, which are implemented in every filter, are:
- **applyFilter** - applying filter on filtration handler
- **convertValue** - converts raw value (e.g. set by a form) into a "workable" and clean format

If you build your own filter, you can easily extend this class and it will give you maximum capabilities listed above.

However, it is not required to extend AbstractFilter for your own filters, if you don't need all of these functions.

Keep in mind, that:
- The only essential requirement to build a filter is to have it implemented **FilterInterface**.
- If you want your filter to be able to have an option setting (array('foo' => 'bar') instead of $filter->setFoo('bar')), the filter must implement **FilterOptionInterface**.
- If you want your filter to be able to handle form,  the filter must implement **FilterHasFormInterface**.

Learn more about filters [here](filters-reference.md).

### Filter creator

Filter creator is responsible for creating filters. An example:

```php
$creator = $serviceContainer->get('da2e.filtration.filter.creator.filter_creator');

// 1st argument is the alias of filter service definition.
// 2nd argument is the name of the filter. If it is omitted, a random unique name will be generated.
// 3rd argument is an array of filter options.
$filter = $creator->create('da2e_doctrine_orm_text_filter', 'filter_name', ['filter' => 'options']);
```

Filter creator is used in conjunction with filter option handler, if options are passed while creating a filter. To use this feature, the filter must implement **FilterOptionInterface**.

### Filter option handler

Default filters provide a variety of setters for configuring its behaviour and properties. Sometimes, it is more convenient to have some sort of single option bag instead of different setter methods, for example if the options are generated dynamically by multiple conditions, or if you just prefer more shorter way of setting options.

Filter option handler exists exactly for this purpose: basically, it handles an array of options for filter and transforms them into setters, which are executed as usual.

An example:

```php
$optionHandler = $serviceContainer->get('da2e.filtration.filter.filter_option.filter_option_handler.');

$filter = new SomeFilter('foo_filter'); // or create via filter creator component
$optionHandler->handle($filter, ['bar' => 123, 'baz' => 321]);
```

If you use filter creator component, than there is no need for you to use filter option handler separately, because filter creator does work in conjunction with option handler.

Note, that option handler can handle options only for the filter (in this case, SomeFilter) being implemented **FilterOptionInterface**, otherwise an exception will be thrown.

**FilterOptionInterface** describes only one method - **getValidOptions()**. This method must return an array, which describes available options. The options basically are an array which maps option names to a setter methods with few other parameters.

An example of available options in **getValidOptions()** method:

```php
public static function getValidOptions()
{
    return [
        'foo' => [
            'setter' => 'setFoo',
            'type'   => 'string',
            'empty'  => false,
        ],
        'bar' => [
            'setter' => 'setBar',
            'type'   => ['int', 'float'],
            'empty'  => true,
        ],
        'baz' => [
            'setter'      => 'setBaz',
            'type'        => 'object',
            'instance_of' => '\stdClass',
        ],
    ];
}
```

Consider the following:
- tye 'type' key is not required and in this case the type of the value will not be checked
- the 'type' key can contain just one type (as string), or multiple types as array of strings
- the type must be a string, which can be joined with 'is_' prefix to form a PHP function (e.g. 'is_string')
- the 'empty' key is not required and if it is not set, it is considered, that the option may be empty
- if the type is "object", the 'instance_of' key may be set with class FQN as value

### Filter collection creator

Filter collection creator is responsible for creating a filter collection. Basically, it is a factory with one simple factory method.

```php
$collectionCreator = $serviceContainer->get('da2e.filtration.filter.collection.creator.collection_creator');

// Simple as it is!
$collection = $collectionCreator->create();
```

### Filter collection

Filter collection is a collection class, which allows to add/remove/get filters. It is required by filter executor and form creator components.

You can simply create it like this:

```php
$collection = new \Da2e\FiltrationBundle\Filter\Collection\Collection();
```

But **it is recommended to use filter collection creator** component (see above section).

### Filter executor

Filter executor is one of the key components which handles the filtration itself - it executes applied filters. "Execution" can be interpreted differently, and it depend on a specific filtration handler. For example, if filtration handler is Doctrine ORM, by "execution" is meant setting **andWhere()** methods for applied filters on the query builder object.

An example:

```php
// Create filter collection
$collectionCreator = $serviceContainer->get('da2e.filtration.filter.collection.creator.collection_creator');
$collection = $collectionCreator->create();

// Create demo filters
$creator = $serviceContainer->get('da2e.filtration.filter.creator.filter_creator');
$titleFilter = $creator->create('da2e_doctrine_orm_text_filter', 'news_title', ['field_name' => 'news.title']);
$contentFilter = $creator->create('da2e_doctrine_orm_text_filter', 'news_content', ['field_name' => 'news.content']);

// Add filters to the collection
$collection->addFilter($titleFilter);
$collection->addFilter($contentFilter);

// Value will be probably set by form in real world
$titleFilter->setValue('applied value');

// The query builder
$queryBuilder = $repository->createQueryBuilder('news');

// Execute applied filters
$executor = $serviceContainer->get('da2e.filtration.filter.executor.filter_executor');

// Filter executor must take filter collection as 1st argument and array of filtration handlers as 2nd argument
$executor->execute($collection, [$queryBuilder]);
```

In the example above only $titleFilter will be executed and $contentFilter will be ignored, because value was "applied" only for $titleFilter.

The second argument of the **execute()** method must contain an array of filtration handlers. Most of the time it will probably contain only one handler, but if it is required to have more handlers, it is possible to pass multiple elements. It is also possible to pass handlers in 2 ways (you can choose any preferred):

```php
// Multiple filtration handlers.
$executor->execute($collection, [$queryBuilder, $sphinxClient]);

// Passing filtration handler without explicitly specifying its type.
// In this case filter executor will try to guess handler type by its object.
// If filter executor will fail at guessing filtration handler type, an exception will be thrown.
$executor->execute($collection, [$queryBuilder]);

// Passing filtration handler with explicit specifying its type.
// In this case filter executor will not try to guess filtration type, because it was explicitly set.
$executor->execute($collection, ['doctrine_orm' => $queryBuilder]);
```

Note, that filtration types must be properly enabled:
- if you use custom filtration handler, just enable it in FiltrationBundle configuration:
```yaml
# app/config/config.yml

da2e_filtration:
    handlers:
        # for example, "Redis" is your custom filtration handler
        redis:
            name: redis
            class: \Your\Path\To\Redis\Filtration\Handler
```
- if you use some of the existing filtration adapter bundles (e.g. Da2e FiltrationDoctrineORMBundle), just be sure to enable the bundle in AppKernel file:
```php
// app/AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        // ...
        $bundles = [
            // ...
            new \Da2e\FiltrationDoctrineORMBundle\Da2eFiltrationDoctrineORMBundle(),
        ];

        // ...

        return $bundles;
    }

    // ...
}
```

If you need to redefine filtration handler of the existing filtration adapter bundle, you can do it in the same way as defining your own custom handlers:
```yaml
# app/config/config.yml

da2e_filtration:
    handlers:
        # for example, your need a custom Doctrine ORM handler
        doctrine_orm:
            name: doctrine_orm # it is important to the existing name of filtration adapter to override it
            class: \Your\Path\To\Doctrine\ORM\Custom\Handler
```

### Form creator

Form creator is a wrapper of Symfony FormFactory component and is responsible for creating a filtration form based on a filter collection:

```php
// $collection = ...;

$formCreator = $serviceContainer->get('da2e.filtration.form.creator.form_creator');
$form = $formCreator->createNamed('filters', $collection);

// Call $form->createView() to pass the form to the template.
```

Form creator offers two methods for creating forms - **create()** and **createNamed()**, which differ only that the first [creates unnamed form](http://api.symfony.com/2.7/Symfony/Component/Form/FormFactory.html#method_create), while the second [creates named form](http://api.symfony.com/2.7/Symfony/Component/Form/FormFactory.html#method_createNamed).

The form will contain child filters in the same order they are defined in the filter collection. Also, the filter must implement **FilterHasFormInterface** to be included in the filtration form.

It is possible to pass 2 additional array-arguments to both of create/createNamed methods:

- 1st contains an array of options for the root form builder (fully compatible with FormFactory::create() and FormFactory::createNamed() methods)
- 2nd contains an array of options for the filter form builder (**the same set of options are applied to every filter**; fully compatible with FormFactory::create() and FormFactory::createNamed() methods)

## Filtration form

As it was already mentioned, the bundles core feature is a form support.

FiltrationBundle uses standard Symfony form objects to provide this possibility. The form is being mapped with fields and populated from the filter collection.

The form can be created via form creator component and passed to the template as FormView object.

[Learn more about form creator component](form-creator.md).

### Filter form type

To create a form field for each filter, it is required to have a prototype of form type object.

The bundle is packaged with a default one filter form type - **FilterType**, which is used for every filter.
It is just an instance of Symfony FormTypeInterface with minimal configuration in it.

If for some reason you need your own form type object, you can easily override it in configuration.

[Learn more about form creator component](form-filter-type.md).

### Filters in templates

Since FiltrationBundle uses standard Symfony form objects and forms are being passed as FormView objects to templates, it is possible to do anything you would do with any other forms in templates.
This also means that you can use any template engine, which Symfony supports.

## How to glue everything together

The bundle is packaged with a single manager, which is responsible for all workflow and all components.
The manager is named as **FilterSuperManager**. It is not really proper way to handle things like this, with one big manager, however sometimes it may be convenient to work with single tool rather than with many separate components.
Basically, FilterSuperManager is just a wrapper with all necessary components inside.

I would suggest to use FilterSuperManager if your filtration requirements does not lead to complicated infrastructure in your code (e.g. handle all filtration workflow in an action of a controller).
In opposite, if you have a complex layer of filtration architecture, I assume it would be more correctly to use separate filtration components (via DI), which would also give an additional control over whole workflow.

Anyway, you are free to choose whether to use FilterSuperManager or separate components.

Check out the examples of usage through different ways:
- [Complete usage example via FilterSuperManager](example-complete-usage-via-filtersupermanager.md)
- [Complete usage example via separate components](example-complete-usage-via-separate-components.md)

## Components customization

Each of the components is fully customizable, which means you can override any of them just by redefining a specific parameters in parameters file (app/parameters.yml) or by configuring them in bundle configuration (app/config.yml).

For example, to override filter collection component, you can just redefine the following parameter:
```yaml
# app/parameters.yml

parameters:
    da2e.filtration.filter.collection.collection_class: Your\Collection\Class
```

For more details on configuration please refer to:
- [Configuration reference](config-reference-config.md)
- [Configuration reference via container parameters](config-reference-parameters.md)
- [Example of customization and override of standard bundle components](example-customize-standard-components.md)
