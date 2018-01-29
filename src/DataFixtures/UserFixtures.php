<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Utils\Slugger;
use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\Entity\Auth;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
    	$this->encoder = $encoder;
	}
	public function load(ObjectManager $manager)
	{

        // Objet Faker pour données fictive loacal FR
        $faker = \Faker\Factory::create('fr_FR');

        /**
         * FIXTURES USERS
         */

        // ADMIN
        $listUsers = [
          [
            'name'    => 'Valerie',
            'surname' => 'Taormina',
            'url'     => 'https://s3.amazonaws.com/uifaces/faces/twitter/zeldman/128.jpg'],
          [
            'name'    => 'Guillaume',
            'surname' => 'Michel',
            'url'     => 'https://s3.amazonaws.com/uifaces/faces/twitter/iannnnn/128.jpg'],
          [
            'name'    => 'Allan',
            'surname' => 'Cafournet',
            'url'     => 'https://s3.amazonaws.com/uifaces/faces/twitter/jsa/128.jpg'],
          [
            'name'    => 'Nicolas',
            'surname' => 'Scuiller',
            'url'     => 'https://s3.amazonaws.com/uifaces/faces/twitter/faulknermusic/128.jpg'],
          [
            'name'    => 'User',
            'surname' => 'User',
            'url'     => 'https://s3.amazonaws.com/uifaces/faces/twitter/sauro/128.jpg'],
          [
            'name'    => 'Naturalist',
            'surname' => 'Naturalist',
            'url'     => 'https://s3.amazonaws.com/uifaces/faces/twitter/zack415/128.jpg'],
          [
            'name'    => 'Admin',
            'surname' => 'Admin',
            'url' => 'https://s3.amazonaws.com/uifaces/faces/twitter/k/128.jpg']
        ];
        $ref = 0;

        foreach ($listUsers as $k => $u) {

          for ($i=0; $i < 3 ; $i++) {

            $mail = $faker->randomElement($array = ['gmail.com','hotmail.fr','yahoo.fr']);

            $user = new User();
            // COMMON
            $user->setName($u['name']);
            $user->setSurname($u['surname']);
            $user->setIsActive(1);
            $user->setUsername(strtolower($user->getName()));
            $user->setMail(Slugger::slugify($user->getName().' '.$user->getSurname().' '.($i+1)).'@'.$mail);

            // ADMIN
            if ($i == 0) {
            $user->setRoles(['ROLE_ADMIN']);
            $user->setUsername(strtolower($user->getName())."admin");
            // NATURALIST
            } elseif ($i == 1) {
              $user->setRoles(['ROLE_NATURALIST']);
              $user->setUsername(strtolower($user->getName())."naturalist");
            // USER
            } elseif ($i == 2) {
              $user->setRoles(['ROLE_USER']);
              $user->setUsername(strtolower($user->getName())."user");
            }
            $encodedPassword= $this->encoder->encodePassword($user,strtolower($user->getName())."password");
            $user->setPassword($encodedPassword);
            $user->setSalt('');
            $user->setNewsletter(rand(1,2));

            $picture = new Picture();
            $picture->setUrl($u['url']);
            $picture->setAlt('avatar');
            $user->setPicture($picture);
            // On sauvegarde $picture;
            $manager->persist($picture);

            $this->addReference('user-'.$ref, $user);
            $ref ++;
            $manager->persist($user);
          }
        }
        // On enregistre en BDD;
        $manager->flush();

	}
    /**
     * Get the order of this fixture
     * @return integer
     */
    public function getOrder()
    {
      return 2;
    }
}