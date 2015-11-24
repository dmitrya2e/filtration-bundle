# Usage example via separate components

TODO

## Prerequisites of the example

- This example creates 2 filters (name and price)
- Template engine in this example is Twig
- Filtration form is created and passed to the template
- This example uses Doctrine ORM query builder as filtration handler, so you must enable it in bundle configuration:
```yaml
# app/config/config.yml
da2e_filtration:
    handlers:
        doctrine_orm: true
```

### Controller

Please, read carefully comments in the code. They are intended to explain what is happening in the example.

```php
// YourController.php

public function yourAction(Request $request)
{
    
}
```

### View

Filtration form is standard Symfony Form object, so you can pass a FormView ($form->createView()) to the template and use it as usual.

```php
// YourController.php

public function yourAction(Request $request)
{
    return $this->render('your/template.html.twig', [
        'form' => $form->createView(),
    ]);
}
```

Template does not contain any special logic for rendering form - everything is done through standard Symfony/Twig functions.
Template engine in this example uses Twig, but since filtration form is a Symfony Form object, it is possible to use any preferable template engine.

```twig
# template.html.twig

<form action="..." method="GET">
    # Filtration form is standard Symfony form view object, so you could do anything you would do with forms in Twig.
    {{ form_row(form.filters.name) }}
    {{ form_row(form.filters.price) }}
    
    <input type="submit"/>
</form>
```
