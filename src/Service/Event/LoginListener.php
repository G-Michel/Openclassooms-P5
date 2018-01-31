<?php 


namespace App\Service\Event;

use App\Service\Event\NotificationInstaller;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;



class LoginListener
{

	private $notificationInstaller;

	public function __construct(NotificationInstaller $notificationInstaller)
	{
		$this->notificationInstaller = $notificationInstaller ;
	}
	

	public function onUserConnect(InteractiveLoginEvent $event)
	{
		$this->notificationInstaller->putNotifOnSession();
		
	}


}