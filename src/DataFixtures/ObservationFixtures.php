<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Bird;
use App\Entity\User;
use App\Entity\Taxref;
use App\Utils\Slugger;
use App\Entity\Picture;
use App\Entity\Location;
use App\Entity\Observation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ObservationFixtures extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        // Objet Faker pour données fictive loacal FR
        $faker = \Faker\Factory::create('fr_FR');

        /**
         * FIXTURES OBSERVATION
         */

        for ($t=0; $t < 100 ; $t++) {

          // Variables
          $taxref  = $this->getReference('taxref-'.$t);
          $slug    = Slugger::slugify($taxref->getLbNomType());
          $frType  = $taxref->getFrType();
          $numFile = rand(0,36);
          $nbBird = rand(1,20);
          $sizeBird = rand(1,4);
          $colorsBird = $faker->randomElements(
            $array = ['noir','marron','blanc','brun','rouge','bleu','jaune'],
            $count = rand(1,3)
          );
          $nbObs = rand(10,50);

          for ($i=0; $i < $nbObs ; $i++) {
            // Variables
            $status = rand(0,1);
            $dateObs = $faker->dateTimeBetween($startDate = '-10 years', $endDate = '- 5 days');
            $user   = $this->getReference('user-'.rand(4,63));
            // Lecture des fichiers Communes CSV
            $numFile ==36?$numRow=rand(0,699):$numRow=rand(0,999);
            $arrayLocations = array_map(function($rowLocation) {
              return explode(',', $rowLocation);
            },array_map('trim', file(__DIR__."/Communes/AllCommune_".$numFile.".csv", FILE_SKIP_EMPTY_LINES)));
            // Ajout d'une observation
            $observation = new Observation();
            $observation->setDateObs($dateObs);
            $observation->setDateAdd($observation->getDateObs()->add(new \DateInterval('P5D')));
            $observation->setStatus($status);

            $observation->setComment('');
            $observation->setUser($user);
            // Ajout d'une location pour l'observation
            $location = new Location();
            $location->setGpsX($arrayLocations[$numRow][19]);
            $location->setGpsY($arrayLocations[$numRow][20]);
            $location->setAddress(" , ".$arrayLocations[$numRow][8]." ".$arrayLocations[$numRow][5].", France");
            $observation->setLocation($location);
            // On sauvegarde $location;
            $manager->persist($location);
            // Ajout d'un oiseau pour l'observation
            $bird = new Bird();
            $bird->setBirdNumber($nbBird);
            $bird->setBirdColors($colorsBird);
            $bird->setBirdSize($sizeBird);
            $bird->setTaxref($taxref);
            $observation->setBird($bird);
            // On sauvegarde $bird;
            $manager->persist($bird);
            // On sauvegarde $observation;
            $manager->persist($observation);
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
      return 3;
    }

}