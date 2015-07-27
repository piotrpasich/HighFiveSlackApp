<?php

namespace XTeam\GitMetricsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use XTeam\HighFiveSlackBundle\Entity\DataManipulator\Period;
use XTeam\HighFiveSlackBundle\Statistic\HighFivesCollection;
use XTeam\HighFiveSlackBundle\Statistic\CollectingStrategy\UserCollectingStrategy;

class ActivityRepository extends EntityRepository
{

    /**
     * @return HighFivesCollection
     */
    public function getStats(Period $period = null)
    {
        $queryBuilder = $this->createQueryBuilder('a');

        if (null !== $period) {
            $queryBuilder = $period->manipulateQuery($queryBuilder, 'a');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
