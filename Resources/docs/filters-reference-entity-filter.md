# Entity filter (abstraction)

[Return to the documentation index page](index.md)

The entity filter is a filter to handle choice-values via Doctrine entities (for both single or multiple modes).

FQN of the class is **\Da2e\FiltrationBundle\Filter\Filter\AbstractEntityFilter**.

## Filter options

The entity filter options are fully extended from **AbstractFilter**.

## Form handling

The entity filter is represented by a **entity** form type, which can be overrided via option **form_field_type** or setter method **setFormFieldType**.

The entity filter is appended to a form by form builder object with the following options:

- **expanded**: true
- **multiple**: true
- **required**: false
- **label**: the title of the filter (can be overrided via filter option **title** or setter method **setTitle**)
- **data**: the default value of the filter (can be overrided via filter option **default_value** or setter method **setDefaultValue**)

All of these values can be overrided via filter option **form_options** or setter method **setFormOptions**.

## Value conversion

The entity filter implements **convertValue** method and it has the following logic:

- if the raw value is an instance of Doctrine Collection object, then it is iterated and for every value is being checked that it has method **getId** and if yes, the value of this method will be stored in a converted array; the converted array with IDs will be returned;
- if the raw value is not an instance of Doctrine Collection object, but it still is an object and it has method **getId**, then the value of this method will be placed in an array; the converted array with single value will be returned.
