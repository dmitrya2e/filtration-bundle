<?php

namespace Da2e\FiltrationBundle\Filter\FilterOption;

use Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionException;
use Da2e\FiltrationBundle\Exception\Filter\FilterOption\FilterOptionValidatorException;
use Da2e\FiltrationBundle\Filter\Filter\FilterOptionInterface;

/**
 * The default filter option handler class.
 * Used for handling filter options in more shortened and convenient way.
 *
 * Each option can be set via filter object method, e.g.:
 *  - $filter->setName('name');
 *  - $filter->setFieldName('field_name');
 *  - $filter->setTitle('title');
 *  - ...
 *
 * This handler is aimed to simplify this process using option array approach:
 *  - FilterOptionHandler::handle($filter, [
 *      'name'       => 'name',
 *      'field_name' => 'field_name',
 *      'title'      => 'title',
 *  ]);
 *
 * @package Da2e\FiltrationBundle\Filter\FilterOption
 */
class FilterOptionHandler implements FilterOptionHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @return FilterOptionInterface
     */
    public function handle(FilterOptionInterface $filter, array $options)
    {
        if (count($options) === 0) {
            return $filter;
        }

        $validOptions = $filter->getValidOptions();

        $this->validateFilterOptions($options, $validOptions);
        $this->setFilterOptions($filter, $options, $validOptions);

        return $filter;
    }

    /**
     * Validates filter options.
     *
     * @param array $options      User set options
     * @param array $validOptions FilterOptionInterface::getValidOptions()
     *
     * @return static
     * @throws FilterOptionException On unexpected errors (e.g. function for type checking does not exist)
     * @throws FilterOptionValidatorException On validation error
     */
    protected function validateFilterOptions(array $options, array $validOptions)
    {
        foreach ($options as $option => $value) {
            // Check if the option is allowed.
            if (!array_key_exists($option, $validOptions)) {
                throw new FilterOptionValidatorException(sprintf(
                    'Option "%s" is not found in valid option list: [%s].',
                    $option, implode(', ', array_keys($validOptions))
                ));
            }

            // Check the type if it is required.
            if (array_key_exists('type', $validOptions[$option])) {
                $types = $validOptions[$option]['type'];

                // Convert single type into array of types.
                if (!is_array($types)) {
                    $types = [$types];
                } elseif (count($types) === 0) {
                    throw new FilterOptionException('Type option must not be empty.');
                }

                $typeCount = count($types);
                $typeCheckedCount = 0;
                $valueType = '';

                foreach ($types as $type) {
                    $typeCheckedCount++;
                    $typeFunction = sprintf('is_%s', $type);

                    if (!function_exists($typeFunction)) {
                        throw new FilterOptionException(sprintf('There is no such function "%s".', $typeFunction));
                    }

                    if (!$typeFunction($value)) {
                        if ($typeCount === $typeCheckedCount) {
                            throw new FilterOptionValidatorException(sprintf(
                                'Option "%s" has invalid value type "%s" (which must be "%s").',
                                $option, gettype($value), $type
                            ));
                        }
                    } else {
                        $valueType = $type;
                        break;
                    }
                }

                // Check class instance if it is required.
                if (
                    $valueType === 'object'
                    && in_array('object', $types, true)
                    && array_key_exists('instance_of', $validOptions[$option])
                    && !($value instanceof $validOptions[$option]['instance_of'])
                ) {
                    throw new FilterOptionValidatorException(sprintf(
                        'Option "%s" must be an instance of %s.', $option, $validOptions[$option]['instance_of']
                    ));
                }
            }

            if (
                array_key_exists('empty', $validOptions[$option])
                && $validOptions[$option]['empty'] === false
                && (string) $value === ''
            ) {
                throw new FilterOptionValidatorException(sprintf(
                    'Option "%s" with value "%s" can not be empty.',
                    $option, is_scalar($value) ? (string) $value : '[...]'
                ));
            }
        }

        return $this;
    }

    /**
     * Sets filter options.
     * Options must be validated before setting them.
     *
     * @param FilterOptionInterface $filter
     * @param array                 $options      User set options
     * @param array                 $validOptions FilterOptionInterface::getValidOptions()
     *
     * @return static
     * @throws FilterOptionException On unexpected error (such as if setter method does not exist in filter class, etc)
     */
    protected function setFilterOptions(FilterOptionInterface $filter, array $options, array $validOptions)
    {
        foreach ($options as $option => $value) {
            if (!array_key_exists('setter', $validOptions[$option])) {
                throw new FilterOptionException(sprintf(
                    'Key "setter" is not present in option "%s" restrictions array (filter "%s").',
                    $option, get_class($filter)
                ));
            }

            $optionSetter = $validOptions[$option]['setter'];

            if (!method_exists($filter, $optionSetter)) {
                throw new FilterOptionException(sprintf(
                    'There is no such method "%s" in filter "%s".', $optionSetter, get_class($filter)
                ));
            }

            $filter->$optionSetter($value);
        }

        return $this;
    }
}
