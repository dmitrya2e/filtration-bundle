<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Exception\Filter\Filter;

/**
 * An exception for invalid filter handlers (used in FilterInterface::applyFilter()).
 *
 * @see FilterInterface::applyFilter()
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class InvalidHandlerException extends InvalidArgumentException
{

}
