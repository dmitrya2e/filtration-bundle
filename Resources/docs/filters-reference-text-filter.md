# Text filter (abstraction)

[Return to the documentation index page](index.md)

The text filter is a filter to handle a scalar (text & numbers) values.

FQN of the class is **\Da2e\FiltrationBundle\Filter\Filter\AbstractTextFilter**.

## Filter options

The text filter options are fully extended from **AbstractFilter**.

## Form handling

The text filter is represented by a **text** form type, which can be overrided via option **form_field_type** or setter method **setFormFieldType**.

The text filter is appended to a form by form builder object with the following options:

- **required**: false
- **label**: the title of the filter (can be overrided via filter option **title** or setter method **setTitle**)
- **data**: the default value of the filter (can be overrided via filter option **default_value** or setter method **setDefaultValue**)

All of these values can be overrided via filter option **form_options** or setter method **setFormOptions**.

## Value conversion

The text filter implements **convertValue** method and it has the following logic:

- if the raw value is scalar, it will be casted to string and returned;
- if the raw value is not scalar, an empty string will be returned.
