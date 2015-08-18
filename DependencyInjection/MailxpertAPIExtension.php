<?php

namespace Mailxpert\APIBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MailxpertAPIExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load(sprintf('%s.xml', $config['db_driver']));
        $container->getDefinition('mailxpert_api.access_token_manager')
            ->replaceArgument(2, $config['access_token_class']);


        $loader->load('services.xml');
        $container->getDefinition('mailxpert_api.manager')
            ->replaceArgument(1, $config['oauth']['client_id'])
            ->replaceArgument(2, $config['oauth']['client_secret'])
            ->replaceArgument(3, $config['oauth']['redirect_url'])
            ->replaceArgument(4, $config['oauth']['scope'])
        ;

    }

    public function getAlias()
    {
        return 'mailxpert_api';
    }
}
