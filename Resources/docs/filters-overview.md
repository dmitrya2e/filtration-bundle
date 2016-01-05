# Filters overview

[Return to the documentation index page](index.md)

The only requirement for a filter class to be considered as a valid filter, is to implement \Da2e\FiltrationBundle\Filter\Filter\FilterInterface, which has only 5 methods:
 
- **setName($name)** - sets the internal name of the filter
- **getName()** - gets the internal name of the filter
- **setFieldName($name)** - sets the field name (e.g. in a query builder) of the filter
- **getType()** - gets the type of the filter (e.g. "doctrine_orm")
- **applyFilter** - applies the filtration

This is the minimal amount of methods/required for a filter to work. However, often we need more possibilities. 

FiltrationBundle is packaged with **\Da2e\FiltrationBundle\Filter\Filter\AbstractFilter** class, which has maximum capabilities the bundle can offer:

- to handle filter options via filter option handler
- to 
