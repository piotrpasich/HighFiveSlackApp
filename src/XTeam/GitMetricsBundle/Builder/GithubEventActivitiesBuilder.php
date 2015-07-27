<?php

namespace XTeam\GitMetricsBundle\Builder;

use XTeam\GitMetricsBundle\Entity\Owner;
use XTeam\GitMetricsBundle\Entity\Activity;
use XTeam\GitMetricsBundle\Entity\Repository;
use XTeam\GitMetricsBundle\Matcher\RepositoryMatcher;

class GithubEventActivitiesBuilder implements ActivityBuilderInterface
{

    private $repositoryMatcher;

    public function __construct(RepositoryMatcher $repositoryMatcher)
    {
        $this->repositoryMatcher = $repositoryMatcher;
    }

    public function getActivities($data)
    {
        $type = substr($data['type'], 0, -5);

        $findBy = [
            'externalId' => $data['id'],
            'type'       => $type
        ];
        if (null != ($activity = $this->repositoryMatcher->findBy('activity', $findBy))) {
            return null;
        }

        $activity = new Activity();
        $activity->setExternalId($data['id']);
        $activity->setCreatedAt(new \DateTime($data['created_at']));
        $activity->setOwner($this->getOwner($data));
        $activity->setRepository($this->getRepository($data));
        $activity->setPublic($data['public']);
        $activity->setType($type);

        $builderClassName = "XTeam\GitMetricsBundle\Builder\Github\\" . $type . "Builder";
        if (!class_exists($builderClassName)) {
            throw new \Exception(sprintf("No %s for %s", $type, $builderClassName));
        }
        $activity = (new $builderClassName())->getActivity($activity, $data);

        $this->repositoryMatcher->saveRecord('activity', $findBy, $activity);

        return $activity;
    }

    private function getOwner($data)
    {
        $findBy = ['externalId' => $data['actor']['id']];
        if (null != ($owner = $this->repositoryMatcher->findBy('owner', $findBy))) {
            return $owner;
        }

        $owner = new Owner();
        $owner->setExternalId($data['actor']['id']);
        $owner->setName($data['actor']['login']);

        $this->repositoryMatcher->saveRecord('owner', $findBy, $owner);

        return $owner;
    }

    private function getRepository($data)
    {
        $findBy = ['externalId' => $data['repo']['id']];
        if (null != ($repository = $this->repositoryMatcher->findBy('repository', $findBy))) {
            return $repository;
        }

        $repository = new Repository();
        $repository->setExternalId($data['repo']['id']);
        $repository->setName($data['repo']['name']);

        $this->repositoryMatcher->saveRecord('repository', $findBy, $repository);

        return $repository;
    }
}
