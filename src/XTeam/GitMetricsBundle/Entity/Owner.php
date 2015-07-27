<?php

namespace XTeam\GitMetricsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Owner
 */
class Owner
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    private $externalId;

    private $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Owner
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param mixed $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return mixed
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * @param mixed $pullRequests
     */
    public function addActivity(Activity $activity)
    {
        $this->activities->set($activity->getExternalId(), $activity);

        return $this;
    }
}
