<?php

namespace App\Controller\Admin;

use App\Entity\Observation;
use App\Form\ObservationType;
use App\Repository\ObservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\UserPictureType;
use App\Form\ResetPasswordType;

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
  	* @Method("GET")
  	*/
  	public function editProfil()
  	{
  		$user = $this->get('security.token_storage')->getToken()->getUser();

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


  		
  		return $this->render('admin/editProfil.html.twig',array(
  			'forms' => $views
  		));


  	}


}
