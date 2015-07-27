<?php

namespace XTeam\GitMetricsBundle\Builder\Github;

use XTeam\GitMetricsBundle\Entity\Activity;

class IssueCommentBuilder implements BuilderInterface
{
    public function getActivity(Activity $activity, $data)
    {
        $activity->setTitle(sprintf("Commented issue %s", $data['payload']['issue']['title']));

        return $activity;
    }
}
