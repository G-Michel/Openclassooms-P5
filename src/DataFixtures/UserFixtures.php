<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
    	$this->encoder = $encoder;
	}
	public function load(ObjectManager $manager)
	{
		$listUser = array('bob', 'cousteau' , 'martha' , 'valerie' , 'guillaume', 'allan', 'nicolas');
		$roles = array('ROLE_USER', 'ROLE_NATURALIST' , 'ROLE_ADMIN' );
		$i=0;

		foreach ($listUser as $key => $username ) 
		{
			

			$key>2?$i=0:$i=$key;
			$user = new User();
			$user->setUsername($username);
			$encodedPassword= $this->encoder->encodePassword($user,$username."password");
			$user->setPassword($encodedPassword);
			$user->setMail($username.'@gmail.com');
			$user->setSalt('');
			$user->setRoles(array($roles[$i]));
			$user->setNewsletter(true);
			$i++;
			$manager->persist($user);
		}
		$manager->flush();
	}
}