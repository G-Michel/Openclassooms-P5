<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Location;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\ContactType;
use App\Form\ArticleType;
use App\Entity\Observation;
use App\Form\ObservationType;
use App\Form\ObserveBirdMomentType;
use App\Form\ObserveBirdDetailType;
use App\Form\ObserveBirdLocationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TestFormController extends Controller
{
    /**
     * @Route("/test/form/signUp")
     */
    public function signUp(Request $request)
    {
        $user = new User();
        $form = $this->createForm(SignUpType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # code...
        }

        return $this->render('users/testForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/test/form/signIn")
     */
    public function signIn(Request $request)
    {
        $user = new User();
        $form = $this->createForm(SignInType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # code...
        }

        return $this->render('users/testForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/test/form/contact")
     */
    public function contact(Request $request)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # code...
        }

        return $this->render('users/testForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/test/form/stepOne")
     */
    public function stepOne(Request $request, SessionInterface $session)
    {
        $observation = new Observation();
        $form = $this->createForm(ObserveBirdLocationType::class, $observation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('observation', $observation);
            return $this->redirectToRoute('step_two');
            // return $this->forward('App\Controller\TestFormController::stepTwo', compact('observation'));
        }

        return $this->render('users/testForm.html.twig', [
            'form' => $form->createView(),
            ]);
        }
        /**
         * @Route("/test/form/stepTwo", name="step_two")
         */
        public function stepTwo(Request $request, SessionInterface $session)
        {
            $observation = $session->get('observation');;
            $form = $this->createForm(ObserveBirdMomentType::class, $observation);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $session->set('observation', $observation);
                return $this->redirectToRoute('step_three');
            # code...
        }

        return $this->render('users/testForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/test/form/stepThree", name="step_three")
     */
    public function stepThree(Request $request, SessionInterface $session)
    {
        $observation = new Observation();
        $observation->setDateObs(new \DateTime('21-12-2017 14:04'));
        $observation->setComment('un petit commentaire');
        $form = $this->createForm(ObserveBirdDetailType::class, $observation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # code...
        }

        return $this->render('users/testForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/test/form/blog")
     */
    public function blog(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # code...
        }

        return $this->render('users/testForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/test/form/observation")
     */
    public function observation(Request $request)
    {
        $observation = new Observation();
        $location = new Location();
        $observation->setLocation($location);
        $form = $this->createForm(ObservationType::class, $observation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # code...
        }

        return $this->render('users/testForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
