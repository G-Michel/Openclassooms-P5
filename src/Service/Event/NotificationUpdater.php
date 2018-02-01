<?php

namespace App\Service\Event;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;



class NotificationUpdater
{

	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function updateNotification()
	{

		// un event est fait quand une nouvelle notification est faite/modifiée ? 
		// l'event listener capte ça 
		//le service correspondant capte si un attribut à été modifié et 


		 if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
		$session = $this->container->get("session");


        }


		
		
		
	}
}