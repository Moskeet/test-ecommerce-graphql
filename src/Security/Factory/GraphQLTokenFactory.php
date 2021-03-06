<?php

namespace App\Security\Factory;

use App\Security\Authentication\Provider\GraphQLTokenProvider;
use App\Security\Firewall\GraphQLTokenListener;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GraphQLTokenFactory implements SecurityFactoryInterface
{
    /**
     * Configures the container services required to use the authentication listener.
     *
     * @param ContainerBuilder $container
     * @param string $id The unique id of the firewall
     * @param array $config The options array for the listener
     * @param string $userProvider The service id of the user provider
     * @param string $defaultEntryPoint
     *
     * @return array containing three values:
     *               - the provider id
     *               - the listener id
     *               - the entry point id
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.graphql_token.'.$id;
        $container
            ->setDefinition($providerId, new ChildDefinition(GraphQLTokenProvider::class))
            ->setArgument(0, new Reference($userProvider))
        ;

        $listenerId = 'security.authentication.listener.graphql_token.'.$id;
        $container->setDefinition($listenerId, new ChildDefinition(GraphQLTokenListener::class));

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    /**
     * Defines the position at which the provider is called.
     * Possible values: pre_auth, form, http, and remember_me.
     *
     * @return string
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * Defines the configuration key used to reference the provider
     * in the firewall configuration.
     *
     * @return string
     */
    public function getKey()
    {
        return 'graphql_token';
    }

    public function addConfiguration(NodeDefinition $builder)
    {

    }
}
