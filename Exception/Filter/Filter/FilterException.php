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

use Da2e\FiltrationBundle\Exception\BaseException;

/**
 * Base filter exception which is and must be used in filter classes and its children.
 * Every concrete filter exception extends this class.
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterException extends BaseException
{

}
