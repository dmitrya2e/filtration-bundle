<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

use Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException;

/**
 * Base callable function validator, which has all "core" functionality for validating callable functions.
 * Tis class should be extended by all concrete validators.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>s
 */
class BaseFunctionValidator
{
    /**
     * Allowed argument type names in callable function.
     * It must consist of array with the following structure:
     *  array(
     *      0 => array('type' => 'Fully\Qualified\Name'),
     *      1 => array('type' => null, 'array' => true),
     *      n => ...,
     *  );
     *
     * Key "type" defines the FQN of the class or NULL, if the argument is passed without type hinting.
     * Key "array" defines if the argument must be type of array ("type" key must be equal to NULL).
     *
     * @var array|bool
     */
    protected $argumentTypes = false;

    /**
     * The callable function, which must be validated.
     *
     * @var callable
     */
    protected $callableFunction = null;

    /**
     * Exception of validation.
     *
     * @var CallableFunctionValidatorException|null
     */
    protected $exception = null;

    /**
     * @param callable|null $callableFunction
     */
    function __construct(callable $callableFunction = null)
    {
        $this->callableFunction = $callableFunction;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid()
    {
        try {
            $this->doValidate();
        } catch (\Exception $e) {
            $this->exception = $e;

            return false;
        }

        return true;
    }

    /**
     * Gets validation exception.
     *
     * @return CallableFunctionValidatorException|\Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function setCallableFunction(callable $callableFunction)
    {
        $this->callableFunction = $callableFunction;

        return $this;
    }

    /**
     * Validates callable function arguments.
     *
     * @throws CallableFunctionValidatorException
     * @throws \InvalidArgumentException
     */
    protected function doValidate()
    {
        if ($this->argumentTypes === false) {
            throw new CallableFunctionValidatorException('Variable $argumentTypes must be defined.');
        }

        $argumentCount = count($this->argumentTypes);

        $reflectionFunction = new \ReflectionFunction($this->callableFunction);
        $reflectionParams = $reflectionFunction->getParameters();

        // Checking argument count.
        if (count($reflectionParams) != $argumentCount) {
            throw new CallableFunctionValidatorException(sprintf(
                'There is not sufficient argument count (required %s arguments).', $argumentCount
            ));
        }

        // Checking argument types.
        foreach ($this->argumentTypes as $k => $type) {
            if (!array_key_exists('type', $type)) {
                throw new \InvalidArgumentException('Key "type" must be defined.');
            }

            $parameter = $reflectionParams[$k];
            $class = $parameter->getClass();

            if ($type['type'] !== null) {
                if ($class === null || $type['type'] !== $class->getName()) {
                    throw new CallableFunctionValidatorException(sprintf(
                        'Argument %s must be type of "%s".', $k, $type['type']
                    ));
                }
            }

            if (array_key_exists('array', $type) && $type['array'] === true) {
                if ($class !== null) {
                    throw new CallableFunctionValidatorException(sprintf('Argument %s must be type of null.', $k));
                }

                if (!$parameter->isArray()) {
                    throw new CallableFunctionValidatorException(sprintf('Argument %s must be an array.', $k));
                }
            }
        }
    }
}
