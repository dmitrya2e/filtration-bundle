# Adding a custom filtration handler

[Return to the documentation index page](index.md)

## Add custom filtration handler

It is very easy to add a custom filtration handler. Firstly, register it in bundle configuration:

```yaml
# app/config/config.yml

da2e_filtration:
    handlers:
        your_custom_handler:
            name: your_custom_handler
            class: \Your\Custom\Handler
```

Secondly, edit your filter to return this filtration handler in **getType** method:

```php
// YourCustomFilter.php

namespace YourApp;

use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

class YourCustomFilter implements FilterInterface
{
    // ...

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return 'your_custom_handler';
    }
}
```

That's all, now you can use your custom filtration handler via FiltrationBundle components:

```php
$collectionCreator = $serviceContainer->get('da2e.filtration.filter.collection.creator.collection_creator');
$collection = $collectionCreator->create();

$filter = new YourApp\YourCustomFilter();
$filter->setFieldName('e.foobar');

$collection->addFilter($filter);

$filtrationHandler = $serviceContainer->get('your_custom_filtration_handler');

$filterExecutor = $serviceContainer->get('da2e.filtration.filter.executor.filter_executor');
$filterExecutor->execute($collection, ['your_custom_handler' => $filtrationHandler]);
```

## Override existing filtration handler provided by filtration handler adapter bundle

If you use some of the filtration handler adapter bundle, e.g. Da2eDoctrineORMFiltrationBundle, you can also easily override its default filtration handler:

```yaml
# app/config/config.yml

da2e_filtration:
    handlers:
        doctrine_orm:
            name: doctrine_orm
            class: \Your\Custom\Handler
```

Note, that the name of the handler must be the same as the filtration handler adapter bundle offers. Now you can use your custom filtration handler with filtration handler adapter bundle:

```php
$filterCreator = $serviceContainer->get('da2e.filtration.filter.creator.filter_creator');
$filter = $filterCreator->create('da2e_doctrine_orm_text_filter', 'sample_filter', ['field_name' => 'e.foobar']);

$collectionCreator = $serviceContainer->get('da2e.filtration.filter.collection.creator.collection_creator');
$collection = $collectionCreator->create();
$collection->addFilter($filter);

$filtrationHandler = $serviceContainer->get('your_custom_filtration_handler');

$filterExecutor = $serviceContainer->get('da2e.filtration.filter.executor.filter_executor');
$filterExecutor->execute($collection, ['doctrine_orm' => $filtrationHandler]);
```
