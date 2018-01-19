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
        $listAdmin = [
          ['name' => 'Valerie', 'surname'   => 'Taormina'],
          ['name' => 'Guillaume', 'surname' => 'Michel'],
          ['name' => 'Allan', 'surname'     => 'Cafournet'],
          ['name' => 'Nicolas', 'surname'   => 'Scuiller']
        ];
        $avatar =[
          "https://s3.amazonaws.com/uifaces/faces/twitter/zeldman/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/iannnnn/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/jsa/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/faulknermusic/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/sauro/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/zack415/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/k/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/calebogden/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/iflendra/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/abinav_t/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/ashleyford/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/jadlimcaco/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/csswizardry/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/azielsilas/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/vladabazhan/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/talhaconcepts/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/kastov_yury/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/rem/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/ritu/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/sdw/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/ekvium/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/nckjrvs/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/mlane/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/ripplemdk/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/felipebsb/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/admod/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/kareemhmostafa/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/adellecharles/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/nzcode/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/guiiipontes/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/rogie/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/jm_denis/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/towhidzaman/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/jollynutlet/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/rssems/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/andyvitale/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/dzyngiri/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/davidburlton/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/kerem/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/chadengle/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/tomaslau/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/getsocial_now/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/mizko/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/dancounsell/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/nexy_dre/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/tonypeterson/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/jina/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/sachagreif/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/vladarbatov/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/mattchevy/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/sindresorhus/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/glif/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/vista/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/idiot/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/mghoz/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/marcosmoralez/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/arashmil/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/shalt0ni/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/cemshid/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/tonystubblebine/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/whale/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/adhamdannaway/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/c_southam/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/vladzima/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/_hartjeg/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/eduardo_olv/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/robertovivancos/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/felipenogs/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/abecherian/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/sillyleo/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/enda/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/peterme/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/mattsince87/128.jpg",
          "https://s3.amazonaws.com/uifaces/faces/twitter/jgoillot/128.jpg"
        ];

        for ($i=0; $i < 64 ; $i++) {

          $mail = $faker->randomElement($array = ['gmail.com','hotmail.fr','yahoo.fr']);

          $user = new User();
          // ADMIN
          if ($i < 4) {
            $user->setRoles(['ROLE_ADMIN']);
            $user->setName($listAdmin[$i]['name']);
            $user->setSurname($listAdmin[$i]['surname']);
            $user->setIsActive(1);
            $user->setUsername(strtolower($user->getName()));
            $user->setMail(Slugger::slugify($user->getName().' '.$user->getSurname()).'@'.$mail);
          } else {
            $user->setName($faker->firstname);
            $user->setSurname($faker->lastname);
            $user->setIsActive(1);
            $user->setUsername(Slugger::slugify($user->getName().' '.($i+1)));
            $user->setMail(Slugger::slugify($user->getName().' '.$user->getSurname().' '.($i+1)).'@'.$mail);
            // NATURALIST
            if (3 < $i && $i < 14) {
              $user->setRoles(['ROLE_NATURALIST']);
            // USER
            } else {
              $user->setRoles(['ROLE_USER']);
            }
          }
          $encodedPassword= $this->encoder->encodePassword($user,strtolower($user->getName())."password");
          $user->setPassword($encodedPassword);
          $user->setSalt('');
          $user->setNewsletter(rand(1,2));

          $picture = new Picture();
          $picture->setUrl($avatar[$i]);
          $picture->setAlt('avatar');
          $user->setPicture($picture);
          // On sauvegarde $picture;
          $manager->persist($picture);

          $this->addReference('user-'.$i, $user);
          $manager->persist($user);
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