<?php

namespace XTeam\SlackMessengerAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('XTeamSlackMessengerAppBundle:Default:index.html.twig', array('name' => $name));
    }
}
