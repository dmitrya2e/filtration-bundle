# Callable validators

[Return to the documentation index page](index.md)

## Overview

As it is mentioned in [filters overview section](filters-overview.md), filters might have a custom implementations for some default methods. For example, AbstractFilter has ability to pass custom handlers for methods **applyFilter()**, **appendFormFields()** and others.

"Custom implementations" are based on callable functions. If you care about the correctness of callable function input signature, which I care about, you can validate them with a CallableFunction validators.

For example, to pass a custom implementation of **applyFilter()** method, you must do the following:

```php
$filterCreator = $serviceContainer->get('da2e.filtration.filter.creator.filter_creator');
$filter = $filterCreator->create('da2e_doctrine_orm_text_filter', 'foo', [
    'apply_filter_function' => function(\Da2e\FiltrationBundle\Filter\Filter\FilterInterface $filter, $handler) {
        // your custom code goes here
    },
]);
```

Option **apply_filter_function** maps to setApplyFilterFunction() method, which does the following:

```php
public function setApplyFilterFunction(callable $function)
{
    $validator = $this->getCallableValidatorApplyFilters();
    $validator->setCallableFunction($function);

    if ($validator->isValid() === false) {
        throw $validator->getException();
    }

    $this->applyFilterFunction = $function;

    return $this;
}
```

1. It gets a callable function validator for "apply filter" method.
2. It checks if the passed callable function is valid.
3. If the callable function is invalid, an exception will be thrown. Otherwise the passed callable function is set as new "apply filter" method.

To pass a custom callable function validator for applyFilter() method, you can use the option **callable_validator_apply_filter**:

```php
$filterCreator = $serviceContainer->get('da2e.filtration.filter.creator.filter_creator');
$filter = $filterCreator->create('da2e_doctrine_orm_text_filter', 'foo', [
    'callable_validator_apply_filter' => new \Your\Custom\Callable\Validator(),
]);
```

And that's all, now custom "apply filter" implementation will be checked according to your validator.

**Note, that callable function validators checks only for input signature, i.e. types of passed arguments to the callable function, not values.**

## How to create your own callable validator

Creation of custom callable validator is very simple and fast.

1. Create a class, e.g. \CustomApplyFilterFunctionValidator.
2. The class must extend **\Da2e\FiltrationBundle\CallableFunction\Validator\BaseFunctionValidator**.
3. The class must implement **\Da2e\FiltrationBundle\CallableFunction\Validator\CallableFunctionValidatorInterface**.
4. The only thing you must write in this class is the following:
```php
class CustomApplyFilterFunctionValidator extends BaseFunctionValidator implements CallableFunctionValidatorInterface
{
    // You must define argument types in necessary order.
    // E.g. you want to check that 1st and 2nd arguments are your custom implementations of filter/query builder, and you add a 3rd argument, which must be an array.
    protected $argumentTypes = [
        ['type' => '\Your\Custom\Filter\Class'],
        ['type' => '\Your\Custom\Query\Builder'],
        ['type' => null, 'array' => true]
    ];
}
```

Each argument type is an array, which can contain the following keys:

- **type**
    - if it is **null**, than no type check will be done, however the argument will be checked for the presence
    - if it equals to a fully-qualified name of a class, the argument will be checked to be an instance of this class
    - if it is **null** and additionally the key "array" with value **true** will be passed, than the argument will be checked to be an array
- **array** - must be passed in conjunction with key **type**=**null**. It makes sense to pass only **true** for this key, because it will be checked that the argument is an array. Combination of **type**=**null** and **array**=**false** will act the same, as simply **type**=**null**.

## Default callable function validators

Here you can find a list of default callable function validators provided by FiltrationBundle (all of them are located under the \Da2e\FiltrationBundle\CallableFunction\Validator namespace):

- **AppendFormFieldsFunctionValidator** - validates custom implementation of **appendFormFields()** method.
- **ApplyFiltersFunctionValidator** - validates custom implementation of **applyFilter()** method.
- **ConvertValueFunctionValidator** - validates custom implementation of **convertValue()** method.
- **HasAppliedValueFunctionValidator** - validates custom implementation of **hasAppliedValue()** method.
