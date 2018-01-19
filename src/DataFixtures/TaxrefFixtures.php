<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Taxref;
use App\Utils\Slugger;
use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class TaxrefFixtures extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        // Objet Faker pour données fictive loacal FR
        $faker = \Faker\Factory::create('fr_FR');

        /**
         * FIXTURES TAXREF
         */

        $taxrefLength = 0;

        // Lecture du fichier taxref CSV
        $arrayAves = array_map('trim', file(__DIR__.'/Taxref/TAXREF11.0_ALL__AVES.csv', FILE_SKIP_EMPTY_LINES));

        // Lecture du fichier galerie CSV avec suppression doublon (1 photo = 1 ref Taxref)
        $arrayGalerie = array_map(function($rowGalerie) {
          return explode(';', $rowGalerie);
        },array_map('trim', file(__DIR__.'/Taxref/GALERIEINPN.csv', FILE_SKIP_EMPTY_LINES)));
        $arrayGalerie = $this->unique_multidim_array($arrayGalerie,1);

        // On boucle sur les lignes du fichier taxref
        foreach ($arrayAves as $k => $rowAves) {

          // On détermine chaque colonnes du fichier taxref
          list(
            $regne,$phylum,$classe,$ordre,$famille,$cdNom,$cdTaxsup,$cdRef,$rang,$lbNom,$lbAuteur,$nomComplet,$nomValide,$nomVern,$nomVernEng,$habitat,$fr,$gf,$mar,$gua,$sm,$sb,$spm,$may,$epa,$reu,$sa,$ta,$nc,$wf,$pf,$cli
          ) = explode(';', $rowAves);

          // On vérifie que la classe est bien AVES
          // --> Si oui on rempli l'entité $taxref avec les données nécessaire pour l'app
          if ($classe === 'Aves') {
            $taxref = new Taxref();
            $taxref->setReignType($regne);
            $taxref->setPhylumType($phylum);
            $taxref->setClassType($classe);
            $taxref->setCdNomType($cdNom);
            $taxref->setLbNomType($lbNom);
            $taxref->setLbAuteurType($lbAuteur);
            $taxref->setNomValideType($nomValide);
            $taxref->setNomVernType($nomVern);
            $taxref->setFrType($fr);
            $taxref->setSlug(Slugger::slugify($lbNom));
            // On boucle sur les lignes du fichier galerie
            foreach ($arrayGalerie as $img) {
              // On vérifie que l'image est associé à la ligne taxref en cour de lecture
              // --> Si oui on rempli l'entité $picture avec les données
              // --> Si oui on relie $picture à $taxref
              if ($img[1] === $cdNom) {
                $picture = new Picture();
                $picture->setUrl($img[0]);
                $picture->setAlt($nomComplet);
                $taxref->setPicture($picture);
                // On sauvegarde $picture;
                $manager->persist($picture);
              }
            }
            // On sauvegarde $taxref seulement le P pour le remplissage (sinon utilise trop de memory);
            if (in_array($fr,['P'])) {
              $this->addReference('taxref-'.$taxrefLength, $taxref);
              $taxrefLength++;
            }
            $manager->persist($taxref);
          }
        }

        // On enregistre en BDD;
        $manager->flush();
    }

    // Fonction pour supprimer doublon d'un array multidim
    private function unique_multidim_array($array, $key)
    {
      $temp_array = array();
      $i = 0;
      $key_array = array();

      foreach($array as $val) {
          if (!in_array($val[$key], $key_array)) {
              $key_array[$i] = $val[$key];
              $temp_array[$i] = $val;
          }
          $i++;
      }
      return $temp_array;
    }

    /**
     * Get the order of this fixture
     * @return integer
     */
    public function getOrder()
    {
      return 1;
    }
}