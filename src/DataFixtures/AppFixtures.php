<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Taxref;
use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arrayAves = array_map('trim', file(__DIR__.'/TAXREF11.0_ALL__AVES.csv', FILE_SKIP_EMPTY_LINES));

        $arrayGalerie = array_map(function($rowGalerie) {
          return explode(';', $rowGalerie);
        },array_map('trim', file(__DIR__.'/GALERIEINPN.csv', FILE_SKIP_EMPTY_LINES)));
        $arrayGalerie = $this->unique_multidim_array($arrayGalerie,1);

        // var_dump($arrayGalerie,1);die();

        foreach ($arrayAves as $rowAves) {

          list(
            $regne,$phylum,$classe,$ordre,$famille,$cdNom,$cdTaxsup,$cdRef,$rang,$lbNom,$lbAuteur,$nomComplet,$nomValide,$nomVern,$nomVernEng,$habitat,$fr,$gf,$mar,$gua,$sm,$sb,$spm,$may,$epa,$reu,$sa,$ta,$nc,$wf,$pf,$cli
          ) = explode(';', $rowAves);

          if ($classe === 'Aves') {
            $taxref = new Taxref();
            $taxref->setReignType($regne);
            $taxref->setPhylumType($phylum);
            $taxref->setClassType($classe);
            $taxref->setNomValideType($nomValide);
            $taxref->setNomVernType($nomVern);
            // $taxref->setOrdre($ordre);
            // $taxref->setFamille($famille);
            // $taxref->setGroupe1Inpn($groupe1Inpn);
            // $taxref->setGroupe2Inpn($groupe2Inpn);
            // $taxref->setCdNom($cdNom);
            // $taxref->setCdTaxsup($cdTaxsup);
            // $taxref->setCdSup($cdSup);
            // $taxref->setCdRef($cdRef);
            // $taxref->setRang($rang);
            // $taxref->setLbNom($lbNom);
            // $taxref->setLbAuteur($lbAuteur);
            // $taxref->setNomComplet($nomComplet);
            // $taxref->setNomCompletHtml($nomCompletHtml);
            // $taxref->setNomValide($nomValide);
            // $taxref->setNomVern($nomVern);
            // $taxref->SetNomVernEng($nomVernEng);
            // $taxref->setHabitat($habitat);
            // $taxref->setFr($fr);
            // $taxref->setGf($gf);
            // $taxref->setMar($mar);
            // $taxref->setGua($gua);
            // $taxref->setSm($sm);
            // $taxref->setSb($sb);
            // $taxref->setSpm($spm);
            // $taxref->setMay($may);
            // $taxref->setEpa($epa);
            // $taxref->setReu($reu);
            // $taxref->setSa($sa);

            foreach ($arrayGalerie as $img) {

              if ($img[1] === $cdNom) {

                $picture = new Picture();
                $picture->setUrl($img[0]);
                $picture->setAlt($nomComplet);

                $manager->persist($picture);
                $taxref->setPicture($picture);
              }
            }

            $manager->persist($taxref);


          }
        }


        $manager->flush();
    }

    private function unique_multidim_array($array, $key) {
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
}