<?php

namespace XTeam\GitMetricsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use XTeam\SlackMessengerBundle\Event\MessageEvent;

class ParseCommand  extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('xteam:metrics:parse')
            ->setDescription('Parses metrics')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $activities = $this->getContainer()->get('x_team_git_metrics.github.importer.activities')->import();

        $em->flush();

        $output->writeln(sprintf("%d activities are saved", count($activities)));
    }
}
