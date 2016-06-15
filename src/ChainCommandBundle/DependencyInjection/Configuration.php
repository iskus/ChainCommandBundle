<?php

namespace ChainCommandBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const COMMAND_NAME_KEY = 'command_name';

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('chain_commands');

        $rootNode
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode(self::COMMAND_NAME_KEY)
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
            ->end();

        return $treeBuilder;
    }
}
