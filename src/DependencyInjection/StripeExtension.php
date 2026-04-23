<?php

namespace Cmrweb\StripeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class StripeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('cmrweb.stripe.key.public', $config['public_key']);
        $container->setParameter('cmrweb.stripe.key.private', $config['private_key']);
        $container->setParameter('cmrweb.stripe.return.url', $config['return_url']);
        $container->setParameter('cmrweb.stripe.api_enabled', $config['api_enabled']);
    }
}
