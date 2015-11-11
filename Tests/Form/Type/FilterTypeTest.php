<?php

namespace Da2e\FiltrationBundle\Tests\Form\Type;

use Da2e\FiltrationBundle\Form\Type\FilterType;
use Da2e\FiltrationBundle\Tests\TestCase;

/**
 * Class FilterTypeTest
 * @package Da2e\FiltrationBundle\Tests\Form\Type
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
