<?php

namespace XTeam\GitMetricsBundle\Builder\Github;

use XTeam\GitMetricsBundle\Entity\Activity;

interface BuilderInterface
{

    public function getActivity(Activity $activity, $data);

}