<?php

namespace XTeam\GitMetricsBundle\Builder\Github;

use XTeam\GitMetricsBundle\Entity\Activity;

class ForkBuilder implements BuilderInterface
{
    public function getActivity(Activity $activity, $data)
    {
        $activity->setTitle(sprintf("Forked %s", $data['payload']['forkee']['name']));

        return $activity;
    }
}