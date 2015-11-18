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

/**
 * An interface, which must be used by all callable function validators.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
