<?php 


namespace App\Service\Event;

use App\Service\Event\SetNotifToSession;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;





class LoginListener
{

	private $setNotifToSession;
	private $container;
	private $authorisationCheck;

	public function __construct(AuthorizationCheckerInterface $authorisationCheck, SetNotifToSession $setNotifToSession, Container $container)
	{
		$this->setNotifToSession = $setNotifToSession;
		$this->container = $container;
		$this->authorisationCheck = $authorisationCheck;
	}

	public function onUserConnect()
	{
		$this->setNotifToSession->putNotifOnSession();
	}

	public function whenConnected()
	{
			
	if ($this->container->get('session')->get('notificationUser') != null || $this->container->get('session')->get('notificationUser') == false)
        {
			$this->setNotifToSession->updateNotifOnSession();
		}
	}
}



