# Date filter (abstraction)

[Return to the documentation index page](index.md)

The date filter is a filter to handle dates (without time).

FQN of the class is **\Da2e\FiltrationBundle\Filter\Filter\AbstractDateFilter**. This class extends **AbstractRangeOrSingleFilter**.

## Filter options

The date filter options are fully extended from **AbstractRangeOrSingleFilter**, but also offers its own options:

| Option name | Description                                                     | Type   | Default                      | Can be empty |
| ----------- | --------------------------------------------------------------- | ------ | ---------------------------- | ------------ |
| float       | Defines whether to treat a filter values as floats or integers. | bool   | false                        | No           |

## Form handling

The date filter in single mode is represented by a **date** form type, which can be overrided via option **form_field_type** or setter method **setFormFieldType**.
The date filter in ranged mode is represented by two fields (from/to) as a **date** form type, which can be overrided via options **form_field_type_ranged_from**/**form_field_type_ranged_to** or setter methods **setFormFieldTypeRangedFrom**/**setFormFieldTypeRangedTo**.

The date filter in single mode is appended to a form by form builder object with the following options:

- **required**: false
- **label**: the title of the filter (can be overrided via filter option **title** or setter method **setTitle**)
- **widget**: single_text
- **format**: dd/MM/yyyy

All of these values can be overrided via filter option **form_options** or setter method **setFormOptions**. Note, that you must place form options under the key **single**, e.g.:

```php
$filter->setFormOptions([
    'single' => [
        'required' => true,
        'label'    => 'foobar',
        // ...
    ],
]);
```

The date filter in ranged mode is appended as 2 fields (from/to) to a form by form builder object with the following options:

- **required**: false
- **label**: "da2e.filtration.date_filter.ranged.from.label" for "from" field and "da2e.filtration.date_filter.ranged.to.label" for "to" field (can be overrided via translations)
- **widget**: single_text
- **format**: dd/MM/yyyy

All of these values can be overrided via filter option **form_options** or setter method **setFormOptions**. Note, that you must place form options under the key **ranged_from** for "from" field and **ranged_to** for "to" field, e.g.:

```php
$filter->setFormOptions([
    'ranged_from' => [
        'required' => true,
        'label'    => 'foobar',
        // ...
    ],
    'ranged_to' => [
        'required' => true,
        'label'    => 'foobar2',
        // ...
    ],
]);
```

## Value conversion

The date filter implements **convertValue** method and it has the following logic:

- if the filter is treated in single mode:
  - if the raw value is not instance of \DateTime, null will be returned;
  - otherwise the \DateTime object without time will be returned;
- if the filter is treated in ranged mode:
  - an array with two elements (0 and 1 indexes: from/to) will be returned:
    - "from" value:
      - if the raw "from" value is not instance of \DateTime, null will be returned;
      - otherwise the \DateTime object without time will be returned;
    - "to" value:
      - if the raw "to" value is not instance of \DateTime, null will be returned;
      - otherwise the \DateTime object without time will be returned;
