# Filters overview

[Return to the documentation index page](index.md)

## Filter interface

The only requirement for a filter class to be considered as a valid filter, is to implement **\Da2e\FiltrationBundle\Filter\Filter\FilterInterface**, which has only 5 methods:

- **setName($name)** - sets the internal name of the filter;
- **getName()** - gets the internal name of the filter;
- **setFieldName($name)** - sets the field name (e.g. in a query builder) of the filter;
- **getType()** - gets the type of the filter (e.g. "doctrine_orm"); required to determine the filtration handler;
- **applyFilter** - applies the filtration.

Note that FilterInterface does not have any method regarding a value of the filter. You must decide on your own how to handle the value, e.g. create setValue() method, ....

[Check here](example-create-custom-filter.md) how to create your own filter using only FilterInterface.

## Common abstract filter

Implementing FilterInterface is the minimal requirement needed for a filter to work. However, often we need more possibilities.

FiltrationBundle is packaged with abstract **\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter** class, which has maximum capabilities the bundle can offer:

- handling filter options via filter option handler;
- creating a filter representation in a form;
- convenient overriding of default crucial methods, e.g. applying filter, creating form field, converting value, etc;
- default behaviour in some of the crucial methods, e.g. converting value;
- ability to override default callable function validators.

It is recommended to use AbstractFilter for your own custom filters, however you are free to decide whether to use it or not.

[Check here](example-create-custom-filter.md) how to create your own filter using AbstractFilter.

## Specific abstract filters

However, if we go further, we need even more possibilities, but this time filter-specific. For example, a number filter may have options for handling int/float numbers, or a date/number filter might have options for defining how exactly handle range filter (==, >= or <=).

Not to duplicate the same code in every concrete filter, there is a set of specific abstract filters, which include implementation of some of crucial methods such as **appendFormFieldsToForm()** and **convertValue()**:

- AbstractChoiceFilter
- AbstractEntityFilter
- AbstractDateFilter
- AbstractNumberFilter
- AbstractTextFilter

Each of them is placed under the namespace \Da2e\FiltrationBundle\Filter\Filter.

So if you will use these classes, than in a concrete filter class most of the time you will need only to implement **applyFilter()** method with filter execution code.

**Each filter in open-source filtration adapter bundle must extend appropriate specific abstract filter classes.** E.g. if you are to create open-source filtration adapter bundle (lets say for Redis filtration handler), you must:

- extend \Da2e\FiltrationBundle\Filter\Filter\AbstractTextFilter by text filter;
- extend \Da2e\FiltrationBundle\Filter\Filter\AbstractChoiceFilter by choice filter;
- extend \Da2e\FiltrationBundle\Filter\Filter\AbstractDateFilter by date filter;
- extend \Da2e\FiltrationBundle\Filter\Filter\AbstractNumberFilter by number filter;
- extend \Da2e\FiltrationBundle\Filter\Filter\AbstractEntityFilter by entity filter;
- ...

## AbstractRangeOrSingleFilter

There is also a class **AbstractRangeOrSingleFilter**, which is extended by **AbstractNumberFilter** and **AbstractDateFilter**. Both of them can handle filtration in ranged (min/max boundings) or single (exact matching) mode. To not duplicate a code for these kind of filters, AbstractRangeOrSingleFilter was created to share a common code.

It has 2 crucial abstract methods for implementation:

- **convertSingleValue**: a method for raw value conversion in single mode. Must return a single scalar value;
- **convertRangedValue**: a method for raw value conversion in ranged mode. Must return an array with 2 elements (0 and 1 indexes): min/max values (may be null for both).

It also implements **convertValue** method from AbstractFilter class.

For getting converted value in a single mode, you can use standard method **getConvertedValue** from AbstractFilter.
For getting converted value in a ranged mode, use:

- **getConvertedFromValue**: for getting "from" field value;
- **getConvertedToValue**: for getting "to" field value.

[Check here](example-create-custom-filter.md) how to create your own filter using specific abstract filters.

### Abstract filter options

AbstractFilter offers a set of default options while creating it using filter creator and filter option handler:

| Option name                           | Description                                                                                           | Type     | Default | Can be empty | Instance of class                   |
| ------------------------------------- | ----------------------------------------------------------------------------------------------------- | -------- | ------- | ------------ | ------------------------------------|
| name                                  | The internal name of the filter (also used as a form name).                                           | string   | —       | No           | Not applicable                      |
| field_name                            | The field name of the filter in filtration handler (e.g. Doctrine query builder).                     | string   |         | No           | Not applicable                      |
| default_value                         | Default value of the filter (used in form).                                                           | string   |         | Yes          | Not applicable                      |
| value_property_name                   | The name of the class property containing value.                                                      | string   | value   | No           | Not applicable                      |
| title                                 | The title of the filter (used in form).                                                               | string   |         | Yes          | Not applicable                      |
| form_options                          | Form options (fully compatible with Symfony FormBuilder).                                             | array    | []      | Yes          | Not applicable                      |
| has_form                              | Defines whether the filter has form presentation or not.                                              | bool     | true    | No           | Not applicable                      |
| form_field_type                       | The alias of the form type ([form types](http://symfony.com/doc/current/reference/forms/types.html)). | string   |         | No           | Not applicable                      |
| apply_filter_function                 | Custom implementation of "apply filter" method.                                                       | callable | null    | No           | Not applicable                      |
| append_form_fields_function           | Custom implementation of "append form fields" method.                                                 | callable | null    | No           | Not applicable                      |
| has_applied_value_function            | Custom implementation of "has applied value" method.                                                  | callable | null    | No           | Not applicable                      |
| convert_value_function                | Custom implementation of "convert value" method.                                                      | callable | null    | No           | Not applicable                      |
| callable_validator_apply_filter       | The internal name of the filter (also used as a form name).                                           | object   | false   | No           | *CallableFunctionValidatorInterface |
| callable_validator_append_form_fields | The internal name of the filter (also used as a form name).                                           | object   | false   | No           | *CallableFunctionValidatorInterface |
| callable_validator_has_applied_value  | The internal name of the filter (also used as a form name).                                           | object   | false   | No           | *CallableFunctionValidatorInterface |
| callable_validator_convert_value      | The internal name of the filter (also used as a form name).                                           | object   | false   | No           | *CallableFunctionValidatorInterface |

* Fully-qualified name is **\Da2e\FiltrationBundle\CallableFunction\Validator\CallableFunctionValidatorInterface**.

### AbstractRangeOrSingleFilter options

AbstractRangeOrSingleFilter fully extends options from AbstractFilter, but also offers a set of own options.

| Option name                 | Description                                                                                           | Type   | Default                      | Can be empty |
| --------------------------- | ----------------------------------------------------------------------------------------------------- | ------ | ---------------------------- | ------------ |
| from_postfix                | Applicable for ranged mode only. The postfix of the "from" field name. E.g. filter name is "foo" and from-postfix is "_from", then the "from" field name is "foo_from". This name is used in form. | string | _from                        | No           |
| to_postfix                  | Applicable for ranged mode only. The postfix of the "to" field name. E.g. filter name is "foo" and to-postfix is "_to", then the "to" field name is "foo_to". This name is used in form.           | string | _to                          | No           |
| single                      | Defines whether to handle filter in single mode or ranged.                                                           | bool   | false                        | No           |
| single_type                 | Applicable for single mode only. Defines single mode type (exact, greater, less, ...).                                                      | string | single_exact                 | No           |
| ranged_from_type            | Applicable for ranged mode only. Defines ranged mode type for "from" field (greater/greater or equal).                                                               | string | ranged_from_greater_or_equal | No           |
| ranged_to_type              | Applicable for ranged mode only. Defines ranged mode type for "to" field (less/less or equal).                                             | string | ranged_to_less_or_equal      | No           |
| from_value_property_name    | Applicable for ranged mode only. The name of the class property containing value for "from" field.                                              | string | fromValue                    | No           |
| to_value_property_name      | Applicable for ranged mode only. The name of the class property containing value for "to" field. | string | toValue                      | No           |
| form_field_type_ranged_from | Applicable for ranged mode only. The alias of the form type for "from" field ([form types](http://symfony.com/doc/current/reference/forms/types.html)).                                                       | string |                              | No           |
| form_field_type_ranged_to   | Applicable for ranged mode only. The alias of the form type for "to" field ([form types](http://symfony.com/doc/current/reference/forms/types.html)).                                                 | string |                              | No           |

AbstractRangeOrSingleFilter also offers a constants that are recommended to use:

- **SINGLE_TYPE_EXACT**: "exact" (==) type of filtration in single mode.
- **SINGLE_TYPE_GREATER**: "greater" (>) type of filtration in single mode.
- **SINGLE_TYPE_GREATER_OR_EQUAL**: "greater or equal" (>=) type of filtration in single mode.
- **SINGLE_TYPE_LESS**: "less" (<) type of filtration in single mode.
- **SINGLE_TYPE_LESS_OR_EQUAL**: "less or equal" (<=) type of filtration in single mode.
- **RANGED_FROM_TYPE_GREATER**: "greater" (>) type of filtration in ranged mode for "from" field.
- **RANGED_FROM_TYPE_GREATER_OR_EQUAL**: "greater or equal" (>=) type of filtration in ranged mode for "from" field.
- **RANGED_TO_TYPE_LESS**: "less" (<) type of filtration in ranged mode for "to" field.
- **RANGED_TO_TYPE_LESS_OR_EQUAL**: "less or equal" (<=) type of filtration in ranged mode for "to" field.

### Crucial functions

#### Apply filter

One of the important methods of a filter is an abstract method **applyFilter()**. It takes filtration handler as a single parameter and it's purpose is to apply value and execute filtration for specific filter.

```php
public function applyFilter($handler)
{
    // Assume, that $handler is a Doctrine ORM Query Builder.
    if (!$this->hasAppliedValue()) {
        return;
    }

    $handler
        ->andWhere(sprintf('%s = :%s', $this->getFieldName(), $this->getName()))
        ->setParameter($this->getName(), $this->getConvertedValue());
}
```

Note, that all of the standard abstract filters already implement this method with an appropriate behaviour.

You can set your own implementation of this method by using either setter **setApplyFilterFunction()** or option **apply_filter_function**. Both ways by default accept a callable function with two parameters:

- filter object (**Da2e\FiltrationBundle\Filter\Filter\FilterInterface**)
- handler object (without type hint)

You can customize callable function validator by setting it via setter **setCallableValidatorApplyFilter** or option **callable_validator_apply_filter**.

#### Get type

Abstract method **getType()** returns a type of a filtration handler (e.g. "doctrine_orm") for detecting correct filtration handler in filter executor component.

The real value of this type must se set in a concrete filter class. It must the same as the name of the filtration handler defined in bundle configuration (handlers -> name) or as the name of the filtration handler offered by filtration adapter bundle.

#### Convert value

Abstract method **convertValue()** converts raw value to an appropriate form to work with.
For example, your filter must use only a number (e.g. int type) and somehow (through a form or any other way) the value set via setValue() method is a numeric string, which is not ok, because you need an integer.

You must handle these situations via convertValue() method:

```php
protected function convertValue()
{
    return (int) $this->value;
}
```

Or even more complex example. Your filter needs to drop all strings from an input array-value, except strings which contain substring "foo" (don't know why'd you need a filter like that):

```php
protected function convertValue()
{
    if (!is_array($this->value)) {
        return [];
    }

    $convertedValue = [];

    foreach ($this->value as $v) {
        if (strpos($v, 'foo') !== false) {
            $convertedValue[] = $v;
        }
    }

    return $convertedValue;
}
```

Method **convertValue()** executes only once (the filter contains a cached version of a converted value) when method **getConvertedValue()** is being called.

Note, that all of the standard abstract filters already implement this method with an appropriate behaviour.

You can set your own implementation of this method by using either setter **setConvertValueFunction()** or option **convert_value_function**. Both ways by default accept a callable function with one parameter:

- filter object (**Da2e\FiltrationBundle\Filter\Filter\FilterInterface**)

You can customize callable function validator by setting it via setter **setCallableValidatorConvertValue** or option **callable_validator_convert_value**.

#### Get converted value

Method **getConvertedValue()** returns a converted value (from raw value). When it's happening the first call of this method, protected abstract method **convertValue()** is being executed and its returned value is being cached.

#### Has applied value

Method **hasAppliedValue()** checks if a filter has any applied value. This method uses only **getConvertedValue()** method for retrieving a value.

The default behaviour of this method has the following algorithm:

- if converted value is an array, it is checked that it is not empty (count($value) > 0)
- if converted value is a string, it is checked that it is not empty ($value !== '')
- otherwise converted value will be checked with a function **empty()** (!empty($value))
    - sometimes this behaviour is suitable and sometimes not (e.g. integer 0 must be considered as applied value) - for such cases you must implement checking of applied value yourself

Note, that all of the standard filters already implement this method with an appropriate behaviour.

You can set your own implementation of this method by using either setter **setHasAppliedValueFunction()** or option **has_applied_value_function**. Both ways by default accept a callable function with one parameter:

- filter object (**Da2e\FiltrationBundle\Filter\Filter\FilterInterface**)

You can customize callable function validator by setting it via setter **setCallableValidatorHasAppliedValue** or option **callable_validator_has_applied_value**.

#### Append form fields

Method **appendFormFieldsToForm()** takes one parameter (\Symfony\Component\Form\FormBuilderInterface) and what it does is basically appends a form field to the given FormBuilder:

```php
public function appendFormFieldsToForm(FormBuilderInterface $formBuilder)
{
    $formBuilder->add($this->getValuePropertyName(), $this->getFormFieldType(), $this->getFormOptions());

    return $formBuilder;
}
```

By default AbstractFilter::appendFormFieldsToForm() does nothing, except throwing an exception, because its implementation must be done in child filters.
This method is being called by a form creator component, if the filter implements **\Da2e\FiltrationBundle\Filter\Filter\FilterHasFormInterface** and its method **hasForm()** equals to true.

Note, that all of the standard abstract filters already implement this method with an appropriate behaviour.

You can set your own implementation of this method by using either setter **setAppendFormFieldsFunction()** or option **append_form_fields_function**. Both ways by default accept a callable function with two parameters:

- filter object (**Da2e\FiltrationBundle\Filter\Filter\FilterInterface**)
- form builder object (**Symfony\Component\Form\FormBuilderInterface**)

You can customize callable function validator by setting it via setter **setCallableValidatorAppendFormFields** or option **callable_validator_append_form_fields**.
