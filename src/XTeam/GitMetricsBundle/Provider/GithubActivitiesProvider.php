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
        $organization = $this->client->api('organization');
        $members = $organization->members()->all($orgranization);

        foreach ($members as $member) {
            $events = $this->client->api('user')->publicEvents($member['login']);
            foreach ($events as $event) {
                try {
                    if (null != ($pullRequest = $this->activityBuilder->getActivities($event))) {
                        $activities[] = $pullRequest;
                    }
                } catch (\Exception $e) {
                    //@TODO - log events
                    continue;
                }
            }
        }

        return $activities;
    }
}
