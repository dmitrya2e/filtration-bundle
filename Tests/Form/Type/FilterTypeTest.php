<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\Form\Type;

use Da2e\FiltrationBundle\Form\Type\FilterType;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterTypeTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FilterTypeTest extends TestCase
{
    public function testSetDefaultOptions()
    {
        $filterType = new FilterType();
        $optionResolverMock = $this->getCustomMock('Symfony\Component\OptionsResolver\OptionsResolver', [
            'setDefaults',
        ]);

        $optionResolverMock->expects($this->any())->method('setDefaults')->with([
            'data_class' => 'Da2e\FiltrationBundle\Filter\FilterInterface',
        ]);

        $filterType->setDefaultOptions($optionResolverMock);
    }
}
