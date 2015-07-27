<?php

namespace XTeam\GitMetricsBundle\Builder\Github;

use XTeam\GitMetricsBundle\Entity\Activity;

class WatchBuilder implements BuilderInterface
{
    public function getActivity(Activity $activity, $data)
    {
        $activity->setTitle(sprintf("Watched %s", $data['repo']['name']));

        return $activity;
    }
}
