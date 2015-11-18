<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests;

use Da2e\FiltrationBundle\DependencyInjection\Compiler\FiltrationCompilePass;
use Da2e\FiltrationBundle\FiltrationBundle;

/**
 * Class FiltrationBundleTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
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
