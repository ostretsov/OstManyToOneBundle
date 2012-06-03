<?php
/**
 * User: rufog
 * Date: 5/21/12
 * Time: 12:08 PM
 */
namespace Ost\ManyToOneBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface{
    public function getConfigTreeBuilder(){
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ost_many_to_one');

        $rootNode
            ->children()
                ->scalarNode('items_per_page')->defaultValue('10')->cannotBeEmpty()->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
