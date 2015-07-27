<?php

namespace XTeam\GitMetricsBundle\Chart;

use CpChart\Classes\pData;
use CpChart\Services\pChartFactory;
use XTeam\GitMetricsBundle\Adapter\PChartAdapter;

class ActivityChartDrawer
{
    public function draw($activities)
    {
        $factory       = new pChartFactory();
        $myData        = new pData();
        $pChartAdapter = new PChartAdapter();

        if (!empty($pChartAdapter->getData($activities))) {
            foreach ($pChartAdapter->getData($activities) as $key => $data) {
                $myData->addPoints($data, $key);
            }

            $myData->addPoints(array_keys($pChartAdapter->sortByOwners($activities)), "Options");
        } else {
            $myData->addPoints(VOID);
        }

        foreach ($myData->Data["Series"] as $SerieName => $serie) {
            $myData->Data["Series"][$SerieName]["Max"] = $this->getMax($activities);
        }

        $myData->setAbscissa("Options");

        // create the image and set the data
        $chart = $factory->newImage(900, 380, $myData);
        $chart->drawLegend(700,50);
        $chart->setGraphArea(60, 40, 870, 290);

        $chart->drawText(10, 23, "Open source activity");

        $chart->scaleMinDivHeight = 40;
        $chart->drawScale([
            "Factors" => [5000],
            'LabelRotation' => 45,
            'Mode' => SCALE_MODE_START0
        ]);

        $chart->drawStackedBarChart([
            "Floating0Serie" => "Floating 0",
            "Surrounding" => 10
        ]);

        return $chart;
    }

    private function getMax($activities)
    {
        $max = [];
        foreach ($activities as $activity) {
            if (!isset($max[$activity->getOwner()->getId()])) {
                $max[$activity->getOwner()->getId()] = 0;
            }
            $max[$activity->getOwner()->getId()] += 1;
        }

        return max($max);
    }
}