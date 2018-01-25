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



class AdminController extends Controller
{

  /**
   * @Route("/admin/", name="admin_home")
   * @Method("GET")
   */
	public function homePage(ObservationRepository $observation)
	{
    return new Response("<html><head></head><body>Administration home</body></html>");
  }

}
