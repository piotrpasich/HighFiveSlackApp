<?php

namespace XTeam\GitMetricsBundle\Builder\Github;

use XTeam\GitMetricsBundle\Entity\Activity;

class PullRequestReviewCommentBuilder implements BuilderInterface
{
    public function getActivity(Activity $activity, $data)
    {
        $activity->setTitle(sprintf("Reviewed pull request %s", $data['payload']['pull_request']['title']));

        return $activity;
    }
}
