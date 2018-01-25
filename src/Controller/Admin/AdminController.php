<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


use App\Repository\ObservationRepository;
use App\Entity\Observation;	



class AdminController extends Controller
{

    /**
     * @Route("/admin/", name="admin_home")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
	public function homePage(Request $request)
	{
		$user = $usr= $this->get("security.token_storage")->getToken()->getUser();
		$obsRepository = $this->getDoctrine()->getRepository(Observation::Class);
		$userObservations = $obsRepository->findByUser(5,$user->getUsername());
		$observationsToValid=$obsRepository->findToValid(5);

        return $this->render('admin/espacePersonnel.html.twig',array(
        	'userObservations' => $userObservations,
        	'obsToValid' => $observationsToValid
        ));
  	}



}
