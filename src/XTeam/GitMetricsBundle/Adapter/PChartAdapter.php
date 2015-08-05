<?php

namespace XTeam\GitMetricsBundle\Adapter;

use XTeam\HighFiveSlackBundle\Statistic\HighFivesCollection;

class PChartAdapter
{

    public function getData($activities)
    {
        $types = $this->getAllTypes($activities);
        $ownersActivities = $this->sortByOwners($activities);
        $preparedData = [];

        foreach ($ownersActivities as $ownerName => $ownersActivitiesRecords) {
            foreach ($types as $type) {
                $preparedData[$type][] = $this->getSumOfActivity($ownersActivitiesRecords, $type);
            }
        }

        return $preparedData;
    }

    protected function getSumOfActivity($activities, $type)
    {
        $sum = 0;
        foreach ($activities as $activity) {
            if ($activity->getType() == $type) {
                $sum += 1;
            }
        }

        return $sum;
    }


    public function sortByOwners($activities)
    {
        $newActivities = [];

        foreach ($activities as $activity) {
            if (!isset($newActivities[$activity->getOwner()->getName()])) {
                $newActivities[$activity->getOwner()->getName()] = [];
            }
            $newActivities[$activity->getOwner()->getName()][] = $activity;
        }

        return $newActivities;
    }

    protected function getAllTypes($activities)
    {
        $types = [];

        foreach ($activities as $activity) {
            $types[] = $activity->getType();
        }

        $types = array_unique($types);

        sort($types);

        return $types;
    }
}
