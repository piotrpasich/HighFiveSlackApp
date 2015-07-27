<?php

namespace XTeam\GitMetricsBundle\Builder\Github;

use XTeam\GitMetricsBundle\Entity\Activity;

class CommitCommentBuilder implements BuilderInterface
{
    public function getActivity(Activity $activity, $data)
    {
        $activity->setTitle(sprintf("Commented commit %s", $data['payload']['comment']['url']));

        return $activity;
    }
}
