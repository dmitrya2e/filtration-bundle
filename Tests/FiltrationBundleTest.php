<?php

namespace Da2e\FiltrationBundle\Tests;

use Da2e\FiltrationBundle\DependencyInjection\Compiler\FiltrationCompilePass;
use Da2e\FiltrationBundle\FiltrationBundle;

/**
 * Class FiltrationBundleTest
 * @package Da2e\FiltrationBundle\Tests
 */
class FiltrationBundleTest extends TestCase
{
    public function testBuild()
    {
        $containerMock = $this->getCustomMock('\Symfony\Component\DependencyInjection\ContainerBuilder', [
            'addCompilerPass',
        ]);

        $containerMock->expects($this->once())->method('addCompilerPass')->with(new FiltrationCompilePass());

        $bundle = new FiltrationBundle();
        $bundle->build($containerMock);
    }
}
