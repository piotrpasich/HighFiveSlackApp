<?php

namespace XTeam\GitMetricsBundle\Matcher;

use Doctrine\ORM\EntityRepository;

class RepositoryMatcher
{
    private $repositories = [];

    private $savedRecords = [];

    public function addRepository(EntityRepository $repository, $alias)
    {
        $this->repositories[$alias] = $repository;
        $this->savedRecords[$alias] = [];
    }

    public function findBy($repository, $findBy = [])
    {
        $key = md5(json_encode($findBy));
        if (!isset($this->savedRecords[$repository][$key]) || null == $this->savedRecords[$repository][$key]) {
            $this->savedRecords[$repository][$key] = $this->getRepository($repository)->findOneBy($findBy);
        }

        return $this->savedRecords[$repository][$key];
    }

    public function saveRecord($repository, $findBy = [], $object) {
        $key = md5(json_encode($findBy));
        $this->savedRecords[$repository][$key] = $object;
    }

    private function getRepository($alias)
    {
        return isset($this->repositories[$alias]) ? $this->repositories[$alias] : null;
    }
}
