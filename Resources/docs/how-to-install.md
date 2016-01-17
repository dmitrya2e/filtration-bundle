# How to install

[Return to the documentation index page](index.md)
[Return to the index page](/)

### Step 1: Download the bundle

You can install the bundle via [Composer](https://getcomposer.org/). Open a command console, navigate to the project directory and type the following command:

```sh
composer require da2e/filtration-bundle "2.0.*"
```

This command will automatically download the bundle of the latest stable version.

### Step 2: Enable the bundle

Next, you must enable the bundle in application kernel (app/AppKernel.php):

```php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        // ...
        new Da2e\FiltrationBundle\FiltrationBundle(),
    );
}
```

### Step 3: Set the configuration

It is required to set the minimal configuration in app/config/config.yml to make the bundle work:

```yaml
# app/config/config.yml

da2e_filtration: ~
```

Full configuration reference can be found [here](config-reference-config.md).

### Step 4: Set up filter adapter (filtration handler)

The bundle itself is not packaged with any filtration adapter, so you must include preferable adapter (or even some of them, if required) by your needs:

- [Doctrine ORM](https://github.com/dmitrya2e/filtration-doctrine-orm-bundle/blob/master/Resources/docs/how-to-install.md)
- [Sphinx Client](https://github.com/dmitrya2e/filtration-sphinx-client-bundle/blob/master/Resources/docs/how-to-install.md)

You can always [create custom filtration adapter](example-add-custom-filtration-handler.md) or even [create an open-source filtration adapter bundle](example-create-filtration-handler-bundle.md), which might be found useful by other developers.

[Click here](filters-handlers.md) to find more about filtration handlers.

### Step 5: Prepare the environment

Finally, return to the command console and clear the cache:

```sh
php app/console cache:clear
```

And you are done, the bundle is ready!

### Step 6: Create filters

Check out [the overview of all filtration components and its workflow](overview-of-components-and-workflow.md) to understand how the bundle works or just start with an examples:
- [Complete usage example via FilterSuperManager](example-complete-usage-via-filtersupermanager.md)
- [Complete usage example via separate components](example-complete-usage-via-separate-components.md)
