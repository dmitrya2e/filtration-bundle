<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\DependencyInjection\Compiler;

use Da2e\FiltrationBundle\DependencyInjection\Compiler\FiltrationCompilePass;
use Da2e\FiltrationBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FiltrationCompilerPassTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class FiltrationCompilerPassTest extends TestCase
{
    public function testProcess_NoFilterChainFound()
    {
        $containerMock = $this->getCustomMock('\Symfony\Component\DependencyInjection\ContainerBuilder', [
            'hasDefinition',
            'getDefinition',
            'findTaggedServiceIds',
        ]);

        $containerMock->expects($this->once())->method('hasDefinition')
            ->with('da2e.filtration.filter.chain.filter_chain')
            ->willReturn(false);

        $containerMock->expects($this->never())->method('getDefinition');
        $containerMock->expects($this->never())->method('findTaggedServiceIds');

        $compilerPass = new FiltrationCompilePass();
        $compilerPass->process($containerMock);
    }

    public function testProcess()
    {
        $containerMock = $this->getCustomMock('\Symfony\Component\DependencyInjection\ContainerBuilder', [
            'hasDefinition',
            'getDefinition',
            'findTaggedServiceIds',
        ]);

        $containerMock->expects($this->once())->method('hasDefinition')
            ->with('da2e.filtration.filter.chain.filter_chain')
            ->willReturn(true);

        $definitionMock = $this->getCustomMock('\stdClass', ['addMethodCall']);

        $definitionMock->expects($this->at(0))->method('addMethodCall')
            ->with('addType', [new Reference('foo'), 'foo1']);

        $definitionMock->expects($this->at(1))->method('addMethodCall')
            ->with('addType', [new Reference('baz'), 'baz1']);

        $definitionMock->expects($this->at(2))->method('addMethodCall')
            ->with('addType', [new Reference('bar'), 'bar1']);

        $definitionMock->expects($this->exactly(3))->method('addMethodCall');

        $containerMock->expects($this->once())->method('getDefinition')
            ->with('da2e.filtration.filter.chain.filter_chain')
            ->willReturn($definitionMock);

        $containerMock->expects($this->once())->method('findTaggedServiceIds')
            ->with('da2e.filtration.filter')
            ->willReturn([
                'foo' => [['alias' => 'foo1']],
                'baz' => [['alias' => 'baz1']],
                'bar' => [['alias' => 'bar1']],
            ]);

        $compilerPass = new FiltrationCompilePass();
        $compilerPass->process($containerMock);
    }
}
