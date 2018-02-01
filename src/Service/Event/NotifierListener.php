<?php 


namespace App\Service\Event;


use App\Entity\Notification;
use App\Service\Event\SetNotifToSession;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


class NotifierListener
{

	private $setNotifToSession;
	private $container;

	public function __construct(SetNotifToSession $setNotifToSession,Container $container)
	{
		$this->setNotifToSession = $setNotifToSession;
		$this->container = $container;
	}	

	public function loggedPageLoad(FilterResponseEvent $event)
	{
	
			//$this->setNotifToSession->putNotifOnSession();

		//verrifie si l'utilisateur est connecté 
		// si c'est le cas il met a jour les notifs^^

		
	}


}