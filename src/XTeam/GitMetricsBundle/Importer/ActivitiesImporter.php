<?php

namespace XTeam\GitMetricsBundle\Importer;

use Doctrine\ORM\EntityManager;
use XTeam\GitMetricsBundle\Provider\ActivitiesProviderInterface;

class ActivitiesImporter
{

    /**
     * @var ActivitiesProviderInterface
     */
    private $activitiesProvider;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(ActivitiesProviderInterface $activitiesProvider,
                                EntityManager $em)
    {
        $this->activitiesProvider = $activitiesProvider;
        $this->em = $em;
    }

    public function import()
    {
        $activities = $this->activitiesProvider->getActivities();

        foreach ($activities as $activity) {
            $this->em->persist($activity);
        }

        return $activities;
    }
}
