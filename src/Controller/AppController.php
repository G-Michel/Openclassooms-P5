<?php

namespace App\Controller;


use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class AppController extends Controller
{

    /**
     * @Route("/", name="home")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
	public function homePage()
	{
        if ($this->getUser()) {
            # code...
            return $this->redirectToRoute('admin_home');
        }
        return $this->render('default/home.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
	public function contact(Request $request)
	{
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # code...
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
	}
}
