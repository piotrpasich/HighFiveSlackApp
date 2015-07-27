<?php

namespace XTeam\GitMetricsBundle\Builder\Github;

use XTeam\GitMetricsBundle\Entity\Activity;

class PushBuilder implements BuilderInterface
{
    public function getActivity(Activity $activity, $data)
    {
        $activity->setTitle($data['payload']['commits'][0]['message']);

        return $activity;
    }
}