<?php

namespace Da2e\FiltrationBundle\Filter\Filter;

/**
 * An interface, which must be used by filter to indicate, that the filter can use option array handling approach.
 *
 * @package Da2e\FiltrationBundle\Filter\Filter
 */
interface FilterOptionInterface
{
    /**
     * Gets valid and available options and its restrictions.
     * The method must return an array with the following structure:
     *
     * [
     *  option_name|string => [
     *      'setter'      => setter_method_name|string,
     *      'type'        => required_value_type|string|array,
     *      'instance_of' => class_fqn|string,
     *      'empty'       => bool,
     *  ],
     *  ...
     * ]
     *
     * Consider the following:
     *  - tye 'type' key is not required and in this case the type of the value will not be checked
     *  - the 'type' key can contain just one type (as string), or multiple types as array of strings
     *  - the type must be a string, which can be joined with 'is_' prefix to form a PHP function (e.g. 'is_string')
     *  - the 'empty' key is not required and if it is not set, it is considered, that the option may be empty
     *  - if the type is "object", the 'instance_of' key may be set with class FQN as value
     *
     * @see http://php.net/manual/en/ref.var.php link for available types
     *
     * @return array
     */
    public static function getValidOptions();
}
