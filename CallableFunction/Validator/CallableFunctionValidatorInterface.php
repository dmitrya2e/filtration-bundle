<?php

namespace Da2e\FiltrationBundle\CallableFunction\Validator;

/**
 * An interface, which must be used by all callable function validators.
 *
 * @package Da2e\FiltrationBundle\CallableFunction\Validator
 */
interface CallableFunctionValidatorInterface
{
    /**
     * Checks if callable function is valid.
     *
     * @return bool
     */
    public function isValid();

    /**
     * Sets callable function to validate.
     *
     * @param callable $callableFunction
     *
     * @return mixed
     */
    public function setCallableFunction(callable $callableFunction);
}
