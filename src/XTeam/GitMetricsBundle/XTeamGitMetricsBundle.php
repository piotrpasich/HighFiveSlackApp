<?php

namespace XTeam\GitMetricsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use XTeam\GitMetricsBundle\CompilerPass\RepositoryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class XTeamGitMetricsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RepositoryCompilerPass());
    }
}
