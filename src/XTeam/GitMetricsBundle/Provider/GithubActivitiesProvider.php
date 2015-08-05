<?php

namespace XTeam\GitMetricsBundle\Provider;


use Github\Client;
use XTeam\GitMetricsBundle\Builder\ActivityBuilderInterface;

class GithubActivitiesProvider implements ActivitiesProviderInterface
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ActivityBuilderInterface
     */
    private $activityBuilder;

    private $organizations;

    public function __construct(Client $client,
                                ActivityBuilderInterface $activityBuilder,
                                array $organizations,
                                $clientToken = null)
    {
        $this->client = $client;
        $this->client->authenticate($clientToken, null, Client::AUTH_HTTP_TOKEN);

        $this->activityBuilder = $activityBuilder;
        $this->organizations = $organizations;
    }

    public function getActivities()
    {
        $activities = [];
        foreach ($this->organizations as $organization) {
            $activities = array_merge($activities, $this->getActivitiesForOrganization($organization));
        }

        return $activities;
    }

    private function getActivitiesForOrganization($orgranization)
    {
        $activities = [];

        $members = $this->getAllPossibleMembers($orgranization);

        foreach ($members as $member) {
            $events = $this->client->api('user')->publicEvents($member['login']);
            foreach ($events as $event) {
                try {
                    if (null != ($activity = $this->activityBuilder->getActivities($event))) {
                        $activities[] = $activity;
                    }
                } catch (\Exception $e) {
                    //@TODO - log events
                    continue;
                }
            }
        }

        return $activities;
    }

    private function getAllPossibleMembers($orgranization)
    {
        //organisation
        $apiOrganizationObject = $this->client->api('organization');
        $members = $this->processMembers($apiOrganizationObject->members()->all($orgranization));

        //team
        $teams = $this->client->api('teams')->all($orgranization);
        foreach ($teams as $team) {
            $members = array_merge($members, $this->processMembers($this->client->api('teams')->members($team['id'])));
        }

        return $members;
    }

    private function processMembers($members)
    {
        return array_combine( array_map(function ($member) {
            return $member['login'];
        }, $members), $members);
    }
}
