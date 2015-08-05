<?php

namespace XTeam\GitMetricsBundle\Command;

use Github\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use XTeam\SlackMessengerBundle\Event\MessageEvent;

class ParseOwnersCommand  extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('xteam:metrics:parse:owners')
            ->setDescription('Parses users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientToken = $this->getContainer()->getParameter('github.api.token');
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $githubClient = $this->getContainer()->get('x_team_git_metrics.github.client');
        $githubClient->authenticate($clientToken, null, Client::AUTH_HTTP_TOKEN);
        $githubClient = $githubClient->api('user');

        $owners = $this->getContainer()->get('x_team_git_metrics.repository.owner')->findAll();

        foreach ($owners as $owner) {
            $ownerArray = $githubClient->show($owner->getName());
            $owner->setFullName(strlen($ownerArray['name']) > 0 ? $ownerArray['name'] : $owner->getName());
            $em->persist($owner);

            $output->writeln(sprintf("%s: %s", $owner->getName(), $owner->getFullName()));
        }

        $em->flush();

    }
}
