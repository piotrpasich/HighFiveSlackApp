<?php

namespace XTeam\GitMetricsBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XTeam\GitMetricsBundle\Chart\ActivityChartDrawer;
use XTeam\GitMetricsBundle\Entity\ActivityRepository;
use XTeam\HighFiveSlackBundle\Entity\DataManipulator\Period;

class ChartController extends Controller
{

    /**
     * @var ChartDrawerInterface
     */
    private $chartDrawer;

    /**
     * @var EntityRepository
     */
    private $activitiesRepository;

    public function __construct(ActivityChartDrawer $chartDrawer,
                                ActivityRepository $activitiesRepository)
    {
        $this->chartDrawer = $chartDrawer;
        $this->activitiesRepository = $activitiesRepository;
    }

    public function showAction(Request $request, $period = null)
    {
//        phpinfo();die();
        $activities = $this->activitiesRepository->getStats(new Period($period, $request->attributes->all()));
        $chart = $this->chartDrawer->draw($activities);

        //catch the image
        ob_start();
        $chart->Stroke();
        $image_data = ob_get_contents();
        ob_end_clean();

        return new Response(
            $image_data,
            200,
            ['Content-Type'     => 'image/png',
             'Content-Disposition' => 'inline; filename="chart.png"']
        );
    }
}
