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


class ChartCommand  extends ContainerAwareCommand
{
    protected $chartDir = '/chart/';

    protected function configure()
    {
        $this
            ->setName('xteam:metrics:chart')
            ->setDescription('Generates a chart from last week')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $chartFileName = $this->saveChart();
        $this->getContainer()->get('x_team_slack_messenger.slack.publisher')->publish(
            $this->getContainer()->getParameter('xteam.metrics.publish_channel'),
            str_replace(
                '|chart|',
                $this->getContainer()->getParameter('xteam.metrics.base_url') . $this->chartDir . $chartFileName,
                $this->getContainer()->getParameter('xteam.metrics.message')
            )
        );
    }

    private function saveChart()
    {
        $date = (new \DateTime())->format('Y_m_d');
        $fileName = 'metrics' . $date . '.png';
        $filePath = $this->getChartDirectory() . $fileName;

        $fs = new Filesystem();
        $fs->dumpFile($filePath,
            $this->getContainer()
                ->get('x_team_git_metrics.controller.chart')
                ->showAction(new Request())
                ->getContent());

        return $fileName;
    }

    private function getChartDirectory()
    {
        return $this->getContainer()->getParameter('assetic.write_to') . $this->chartDir;
    }
}
