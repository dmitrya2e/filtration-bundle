<?php

/*
 * This file is part of the Da2e FiltrationBundle package.
 *
 * (c) Dmitry Abrosimov <abrosimovs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Da2e\FiltrationBundle\Tests\DependencyInjection;

use Da2e\FiltrationBundle\DependencyInjection\Configuration;
use Da2e\FiltrationBundle\Tests\TestCase;
use Symfony\Component\Config\Definition\ArrayNode;

/**
 * Class ConfigurationTest
 *
 * @author Dmitry Abrosimov <abrosimovs@gmail.com>
 */
class ConfigurationTest extends TestCase
{
    public function testGetConfigurationTreeBuilder()
    {
        $configuration = new Configuration();

        $result = $configuration->getConfigTreeBuilder();
        $this->assertInstanceOf('\Symfony\Component\Config\Definition\Builder\TreeBuilder', $result);

        /** @var ArrayNode $tree */
        $tree = $result->buildTree();
        $this->assertInstanceOf('\Symfony\Component\Config\Definition\ArrayNode', $tree);
        $this->assertSame('da2e_filtration', $tree->getName());

        $children = $tree->getChildren();
        $this->assertTrue(is_array($children));
        $this->assertArrayHasKey('handlers', $children);
        $this->assertCount(1, $children);

        // Handlers
        $customHandlers = $children['handlers'];
        $this->assertInstanceOf('\Symfony\Component\Config\Definition\PrototypedArrayNode', $customHandlers);

        $prototype = $customHandlers->getPrototype();
        $this->assertInstanceOf('\Symfony\Component\Config\Definition\ArrayNode', $prototype);

        $prototypeChildren = $prototype->getChildren();
        $this->assertTrue(is_array($prototypeChildren));
        $this->assertArrayHasKey('name', $prototypeChildren);
        $this->assertArrayHasKey('class_name', $prototypeChildren);
        $this->assertCount(2, $prototypeChildren);

        $this->assertInstanceOf('\Symfony\Component\Config\Definition\ScalarNode', $prototypeChildren['name']);
        $this->assertInstanceOf('\Symfony\Component\Config\Definition\ScalarNode', $prototypeChildren['class_name']);
        $this->assertNull($prototypeChildren['name']->getDefaultValue());
        $this->assertNull($prototypeChildren['class_name']->getDefaultValue());
    }
}
