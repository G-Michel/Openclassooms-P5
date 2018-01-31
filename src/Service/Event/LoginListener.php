<?php 


namespace App\Service\Event;

use App\Service\UserNotifier;
use App\Entity\Notification;
use App\Entity\User;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


class LoginListener
{

	private $userNotifier;


	

	public function onUserConnect(AuthenticationEvent $event)
	{
		

		$tezdz->okok;
		
	}


}