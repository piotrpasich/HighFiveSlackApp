<?php

namespace XTeam\GitMetricsBundle\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;


class RepositoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('x_team_git_metrics.matcher.chain')) {
            return;
        }
        $definition = $container->getDefinition(
            'x_team_git_metrics.matcher.chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'x_team_git_metrics.matcher'
        );
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addRepository',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }
}
