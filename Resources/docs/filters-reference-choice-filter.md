# Choice filter (abstraction)

[Return to the documentation index page](index.md)

The choice filter is a filter to handle choice-values (for both single or multiple modes).

FQN of the class is **\Da2e\FiltrationBundle\Filter\Filter\AbstractChoiceFilter**.

## Filter options

The choice filter options are fully extended from **AbstractFilter**.

## Form handling

The choice filter is represented by a **choice** form type, which can be overrided via option **form_field_type** or setter method **setFormFieldType**.

The choice filter is appended to a form by form builder object with the following options:

- **choices**: an empty array
- **expanded**: true
- **multiple**: true
- **required**: false
- **label**: the title of the filter (can be overrided via filter option **title** or setter method **setTitle**)
- **data**: the default value of the filter (can be overrided via filter option **default_value** or setter method **setDefaultValue**)

All of these values can be overrided via filter option **form_options** or setter method **setFormOptions**.

## Value conversion

The choice filter implements **convertValue** method and it has the following logic:

- if the raw value is an array, all of its values will be casted to integers, and the converted array will be returned;
- if the raw value is not array and not null, it will be casted to integer and placed inside an array; the converted array with single value will be returned.
