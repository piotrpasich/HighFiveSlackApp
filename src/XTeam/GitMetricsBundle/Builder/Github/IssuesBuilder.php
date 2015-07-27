<?php

namespace XTeam\GitMetricsBundle\Builder\Github;

use XTeam\GitMetricsBundle\Entity\Activity;

class IssuesBuilder implements BuilderInterface
{
    public function getActivity(Activity $activity, $data)
    {
        $activity->setTitle($data['payload']['issue']['title']);

        return $activity;
    }
}
