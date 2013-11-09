<?php
/*
 * This file was delivered to you as part of the YuccaInSituUpdaterBundle package.
 *
 * (c) Rémi JANOT <r.janot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yucca\InSituUpdaterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('yucca_in_situ_updater');

        $rootNode
            ->useAttributeAsKey('alias')
            ->prototype('array')
            ->children()
                ->arrayNode('roles')
                    ->prototype('variable')
                    ->end()
                ->end()
                ->scalarNode('event_suffix')->end()
                ->arrayNode('event_options')
                    ->prototype('variable')
                    ->end()
                ->end()
                ->arrayNode('entities')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('name')->end()
                        ->scalarNode('entity_class_name')->end()
                        ->arrayNode('event_options')
                            ->prototype('variable')
                            ->end()
                        ->end()
                        ->arrayNode('properties')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('hint')->end()
                                    ->booleanNode('can_add_new')->end()
                                    ->scalarNode('type')->end()
                                    ->arrayNode('roles')
                                        ->prototype('variable')
                                        ->end()
                                    ->end()
                                    ->arrayNode('options')
                                        ->prototype('variable')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('fieldsets')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('fieldset_title_translation_key')->end()
                                    ->arrayNode('properties')
                                        ->prototype('array')
                                            ->children()
                                                ->scalarNode('name')->end()
                                                ->scalarNode('hint')->end()
                                                ->booleanNode('can_add_new')->end()
                                                ->scalarNode('type')->end()
                                                ->arrayNode('roles')
                                                    ->prototype('variable')
                                                    ->end()
                                                ->end()
                                                ->arrayNode('options')
                                                    ->prototype('variable')
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;
//        $this->addEntities($rootNode->children()->arrayNode('entities')->children()->arrayNode('fieldset'));

        return $treeBuilder;
    }
}
