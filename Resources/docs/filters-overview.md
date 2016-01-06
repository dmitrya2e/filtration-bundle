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

## Abstract filter

Implementing FilterInterface is the minimal requirement needed for a filter to work. However, often we need more possibilities. 

FiltrationBundle is packaged with abstract **\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter** class, which has maximum capabilities the bundle can offer:

- handling filter options via filter option handler;
- creating a filter representation in a form;
- convenient overriding of default crucial methods, e.g. applying filter, creating form field, converting value, etc;
- default behaviour in some of the crucial methods, e.g. converting value;
- ability to override default callable function validators.

**Each filter in any open-source filtration adapter bundle must extend AbstractFilter class.** 

Also, it is recommended to use AbstractFilter even for your own custom filters, however you are free to decide whether to use it or not.

### Abstract filter options

AbstractFilter offers a set of default options while creating it using filter creator and filter option handler:

| Option name | Description                                                 | Type        | Can be empty | Instance of class |
| ----------- | ----------------------------------------------------------- | ----------- | ------------ | ----------------- |
| name        | The internal name of the filter (also used as a form name). | string      | No           | Not applicable    |
| name        | The internal name of the filter (also used as a form name). | string      | No           | Not applicable    |
| name        | The internal name of the filter (also used as a form name). | string      | No           | Not applicable    |
