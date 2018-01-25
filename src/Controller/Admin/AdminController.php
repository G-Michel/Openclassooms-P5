<?php

namespace App\Controller\Admin;

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
     * @Cache(smaxage="10")
     */
	public function homePage()
	{
		
        return $this->render('test/home.html.twig');
  	}



}
