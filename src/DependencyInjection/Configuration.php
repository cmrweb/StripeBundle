<?php

namespace Cmrweb\StripeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('stripe');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('public_key')
                    ->defaultValue('%env(STRIPE_PUBLIC)%')
                    ->info('Clé publique Stripe (STRIPE_PUBLIC)')
                ->end()
                ->scalarNode('private_key')
                    ->defaultValue('%env(STRIPE_PRIVATE)%')
                    ->info('Clé secrète Stripe (STRIPE_PRIVATE)')
                ->end()
                ->scalarNode('return_url')
                    ->defaultValue('%env(STRIPE_RETURN_URL)%')
                    ->info('URL de retour après paiement (STRIPE_RETURN_URL)')
                ->end()
                ->booleanNode('api_enabled')
                    ->defaultTrue()
                    ->info('Active les endpoints REST /api/stripe/*')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
