<?php

namespace App\Controller\Admin;

use App\Entity\Observation;
use App\Entity\Picture;
use App\Form\ObservationType;
use App\Form\EditProfileType;
use App\Repository\ObservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\UserPictureType;
use App\Form\ResetPasswordType;

class AdminController extends Controller
{

    /**
     * @Route("/admin/", name="admin_home")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
	public function homePage(Request $request)
	{
		$user = $this->get("security.token_storage")->getToken()->getUser();
		$obsRepository = $this->getDoctrine()->getRepository(Observation::Class);
		$userObservations = $obsRepository->findByUser(5,$user->getUsername());
		$observationsToValid=$obsRepository->findToValid(5);

        return $this->render('admin/espacePersonnel.html.twig',array(
        	'userObservations' => $userObservations,
        	'obsToValid' => $observationsToValid
        ));
  	}

  	/**
  	* @Route("/admin/editProfil", name="edit_profil")
  	* 
  	*/
  	public function editProfil(Request $request, UserPasswordEncoderInterface $encoder)
  	{
  		$user = $this->get('security.token_storage')->getToken()->getUser();
      $form = $this->createForm(EditProfileType::class);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid())
      {
        $formData = $form->getData();
        //var_dump($formData);
        //check if a new value has been included
        $toEdit=false;

        /*if ($formData["picture"]!=null && $formData["alt"]!=null )
        {
              if ($user->getPicture()==null)
              {
                $user->setPicture(new Picture()); 
              }
              else
              {
   
              }
        }*/

        if ($formData["name"]!=null && $user->getName()!= $formData["name"])
        {
              $user->setName($formData["name"]);
            $toEdit=true; 
        }

          if ($formData["surname"]!=null && $user->getSurname()!=$formData["surname"])
          {
            $user->setSurname($formData["surname"]);
            $toEdit=true; 

          }

          if ($formData["mail"]!=null && $user->getMail()!=$formData["mail"])
          { 
            $user->setMail($formData["mail"]);
            $toEdit=true; 
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
          if($toEdit)
          {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            try 
            {
              $em->flush();  
            } catch (Doctrine\ORM\ORMException $e) 
            {
              $this->addFlash('flash_error',"une erreur est survenue pour le changement d'infos personnelles");
              $this->redirectToRoute('edit_profil');
            }
          }
           
      }
  		return $this->render('admin/editProfil.html.twig',array(
  			'form' => $form->createView()
  		));
          
  	}
}

/*
     