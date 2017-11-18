<?php

namespace Mailxpert\APIBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mailxpert_api');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->scalarNode('db_driver')->defaultValue('orm')->end()
                ->scalarNode('access_token_class')->isRequired()->end()
                ->arrayNode('oauth')
                    ->children()
                        ->scalarNode('client_id')->isRequired()->end()
                        ->scalarNode('client_secret')->isRequired()->end()
                        ->scalarNode('redirect_url')->isRequired()->end()
                        ->scalarNode('scope')->defaultValue(null)->end()
                        ->scalarNode('api_base_url')->defaultValue(null)->end()
                        ->scalarNode('api_oauth_url')->defaultValue(null)->end()
                    ->end()
                    ->isRequired()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
