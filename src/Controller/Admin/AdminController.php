<?php

namespace App\Controller\Admin;

use App\Entity\Observation;
use App\Entity\Picture;

use App\Form\EditProfileType;
use App\Repository\ObservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends Controller
{
    /**
     * @Route("/admin/", name="admin_home")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
	public function homePage(Request $request, ObservationRepository $observation)
	{
		// $user = $this->get("security.token_storage")->getToken()->getUser();
		// $obsRepository = $this->getDoctrine()->getRepository(Observation::Class);
		// $userObservations = $obsRepository->findByUser(5,$user->getUsername());
    // $observationsToValid=$obsRepository->findToValid(5);
		$userObservations    = $observation->findByUser($this->getUser(),5);
		$observationsToValid = $observation->findEqualToStatus(0,5);

        return $this->render('admin/espacePersonnel.html.twig',array(
        	'userObservations' => $userObservations,
        	'obsToValid'       => $observationsToValid
        ));
    }




    /**
    * @Route("/admin/editProfil", name="edit_profil")
    * 
    */
    public function editProfil(Request $request, UserPasswordEncoderInterface $encoder)
    {
      $picture = new Picture();

      $user = $this->get('security.token_storage')->getToken()->getUser();
      $form = $this->createForm(EditProfileType::class);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid())
      {
        $formData = $form->getData();
        
        //check if a new value has been included
        $toEdit=false;
        $valuesToChange = array("name","surname","mail");

        foreach ($valuesToChange as $value) 
        {
          if ($formData[$value]!=null)
          {
            $toEdit=true;
            $method = "set".$value;
            $user->$method($formData[$value]);
          } 
        }

        if ($formData['picture']->getFile() !== null)
        {
          $toEdit=true;
          $user->setPicture($formData['picture']);
        }

        // is password valid ?
        if ($formData["resetPassword"]["plainPassword"] != null && $formData["currentPassword"] != null) 
        {
          $validCurrentPassword = $encoder->isPasswordValid($user,$formData["currentPassword"]);

          if ($validCurrentPassword)
          {
            $encodedPassword = $encoder->encodePassword($user,$formData["resetPassword"]['plainPassword']);
            $user->setPassword($encodedPassword);
            $toEdit=true; 
          }
        }
        //flush if values entered 
        if($toEdit)
        {
          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          try 
          {
            $em->flush();

          } catch (Doctrine\ORM\ORMException $e) 
          {
            $this->addFlash('flash_error',"une erreur est survenue lors du changement d'infos personnelles");
            $this->redirectToRoute('edit_profil');
          }
          $this->addFlash('Validation',"Vous avez bien mis à jour vos données personnelles");
          $this->redirectToRoute('edit_profil');
        }
        else
        {
              $this->addFlash('Erreur',"Aucunes modifications apportées");
              $this->redirectToRoute('edit_profil');
        }
      }
      return $this->render('admin/editProfil.html.twig',array(
        'form' => $form->createView()
      ));    
    }
}


     