<?php

namespace App\Service\Event;

use App\Entity\Notification;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use App\Repository\NotificationRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;



class NotificationInstaller extends Event
{
	private $request;
	
	private $ticketStorage;
	private $container;

	public function __construct(RequestStack $requestStack,Container $container)
	{
		$this->request = $requestStack->getCurrentRequest();
		$this->container = $container;

	}

	public function putNotifOnSession()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$user = $this->container->get('security.token_storage')->getToken()->getUser();;
		$repository = $em->getRepository(Notification::class);
		$result = $repository->findUserNotifications($user->getId());

		var_dump($result);

	}

	


}
