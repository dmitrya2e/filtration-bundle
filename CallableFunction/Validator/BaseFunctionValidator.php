<?php

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

use Da2e\FiltrationBundle\Exception\CallableFunction\Validator\CallableFunctionValidatorException;

/**
 * Class BaseFunctionValidator
 * @package Da2e\FiltrationBundle\CallableFunction\Validator
 */
class BaseFunctionValidator
{
    /**
     * Allowed argument type names in callable function.
     * It must consist of array with the following structure:
     *  array(
     *      0 => array('type' => 'Fully\Qualified\Name', 'omit' => bool),
     *      1 => array('type' => null, 'omit' => true/false, 'array' => bool),
     *      n => ...,
     *  );
     *
     * Key "type" defines the FQN of the class or NULL, if the argument is passed without type hinting.
     * Key "omit" defines if the validator should validate the argument or not.
     * Key "array" defines if the argument must be type of array.
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
     * Checks if the arguments of the callable functions are valid.
     *
     * @return bool
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
     * @return CallableFunctionValidatorException
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Sets callable function.
     *
     * @param callable $callableFunction
     *
     * @return BaseFunctionValidator
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
            if (array_key_exists('omit', $type) && $type['omit'] === true) {
                continue;
            }

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
