# How to install

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
filtration_bundle:
    handlers:
        doctrine_orm: true # Or any other supported handler, e.g. "sphinx_api"
```

Full configuration reference can be found [here](config-reference-config.md).

### Step 4: Prepare the environment

Finally, return to the command console and clear the cache:

```sh
php app/console cache:clear
```

And enjoy the bundle!