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
        //check if a new value has been add
 
          if ($parameter != null || $parameter != NULL )
          {
            if ($user->getName()!=$form["name"])$user->getName()!=$form["name"];
            if ($user->getName()!=$form["surname"])$user->getName()!=$form["surname"];
            if ($user->getName()!=$form["email"])$user->getName()!=$form["email"];
          
            


          } 
      }
  		return $this->render('admin/editProfil.html.twig',array(
  			'form' => $form->createView()
  		));
  	}
}

/*
      //Generating all forms 
      $forms= array("email"=>'',"name"=>"",'surname'=>"");
      $views;
      foreach ($forms as &$form) $form=$this->createFormBuilder($user);
      $forms['email']->add('mail', EmailType::class,array('label'=>'Adresse email',));
      $forms['name']->add('name', TextType::class,array('label'=>'Nom',));
      $forms['surname']->add('surname',TextType::class,array(
        'label'=>'Nom de famille'));
      foreach ($forms as &$form) $form=$form->getForm();
      $forms['password']= $this->createForm(ResetPasswordType::class);
      $forms['picture'] = $this->createForm(UserPictureType::class);
      foreach ($forms as $key => &$form) $views[$key]=$form->createView();
      foreach ($forms as &$form) $form->handleRequest($request);*/
