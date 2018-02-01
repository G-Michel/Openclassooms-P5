<?php

namespace App\Service\Event;

use App\Entity\Notification;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use App\Repository\NotificationRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;



class SetNotifToSession extends Event
{
	
	
	private $ticketStorage;
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function putNotifOnSession()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$session = $this->container->get('session');
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		$repository = $em->getRepository(Notification::class);
		$result = $repository->findUserNotifications($user->getId());
		$session->set('ghrvduhuibep',$user->getId());
		if ($result == null)
		{
			$session->set('notificationUser',false);
		}
		else
		{
			$session->set('notificationUser',$result);
		}


	}

	public function updateNotifOnSession()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$session = $this->container->get('session');
		$repository = $em->getRepository(Notification::class);
		$result = $repository->findUserNotifications($session->get('ghrvduhuibep'));

		if ($result == null)
		{
			$session->set('notificationUser',false);
		}
		else
		{
			$session->set('notificationUser',$result);
		}



	}

}
