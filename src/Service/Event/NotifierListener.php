<?php 


namespace App\Service\Event;

use App\Service\UserNotifier;
use App\Entity\Notification;

class NotifierListener
{

	private $userNotifier;

	public function __construct(UserNotifier $userNotifier)
	{
		$this->userNotifier = $userNotifier;
	}	

	public function notifyUser()
	{

		
	}


}