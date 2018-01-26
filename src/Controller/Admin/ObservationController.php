<?php

namespace App\Controller\Admin;

use App\Entity\Observation;
use App\Form\ObservationType;
use App\Form\ObserveBirdDetailType;
use App\Form\ObserveBirdMomentType;
use App\Form\ObserveBirdLocationType;
use App\Repository\TaxrefRepository;
use App\Repository\ObservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/observation")
 * @IsGranted("ROLE_USER")
 */
class ObservationController extends Controller
{


    /**
     * @Route("s/", name="admin_observation_index")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function index(ObservationRepository $observation)
    {

      $posts = $observation->findByUser(30,$this->getUser());
      $postsToCheck = $observation->findBy(['status' => 0]);
      return $this->render('admin/observation/index.html.twig', compact('posts','postsToCheck'));
    }

    /**
     * @Route("/ajout", name="observation_new")
     * @Method({"GET"})
     */
    public function new(SessionInterface $session)
    {
        return $this->redirectToRoute('observation_new_step_1');
    }

    /**
     * @Route("/ajout/lieu", name="observation_new_step_1")
     * @Method({"GET", "POST"})
     */
    public function stepOne(Request $request, SessionInterface $session)
    {
        $session->set('step','1');
        $observation = new Observation();
        $form = $this->createForm(ObserveBirdLocationType::class, $observation);
        $options = [
            'page' => [
                "subtitle" => "étape 1 - lieu de l'observation"
            ],
            'form' => [
                "include_back_btn" => false,
                "back_btn_path"    => null
            ]
        ];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('observation', $observation);
            return $this->redirectToRoute('observation_new_step_2');
        }

        return $this->render('admin/observation/new.html.twig', [
            'options' => $options,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajout/moment", name="observation_new_step_2")
     * @Method({"GET", "POST"})
     */
    public function stepTwo(Request $request, SessionInterface $session)
    {
        $session->set('step','2');
        $observation = $session->get('observation');;
        $form = $this->createForm(ObserveBirdMomentType::class, $observation);
        $options = [
            'page' => [
                "subtitle" => "étape 2 - moment de l'observation",
            ],
            'form' => [
                "include_back_btn" => true,
                "back_btn_path"    => "observation_new_step_1"
            ],
            'map' => [
                "address" => $observation->getLocation()->getAddress()
            ]
        ];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('observation', $observation);
            return $this->redirectToRoute('observation_new_step_3');
        }

        return $this->render('admin/observation/new.html.twig', [
            'options' => $options,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajout/oiseau", name="observation_new_step_3")
     * @Method({"GET", "POST"})
     */
    public function stepThree(Request $request, SessionInterface $session)
    {

        $session->set('step','3');
        $observation = $session->get('observation');
        $form = $this->createForm(ObserveBirdDetailType::class, $observation);
        $options = [
            'page' => [
                "subtitle" => "étape 3 - détail de l'observation"
            ],
            'form' => [
                "include_back_btn" => true,
                "back_btn_path"    => "observation_new_step_2"
            ],
            'map' => [
                "address" => $observation->getLocation()->getAddress()
            ]
        ];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($observation);
            $em->flush();
            $session->set('obsId', $observation->getId());
            $session->remove('observation');

            return $this->forward('App\Controller\Admin\ObservationController::stepFor');
        }

        return $this->render('admin/observation/new.html.twig', [
            'options' => $options,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajout/reference/{id}", requirements={"id": "[1-9]\d*"}, name="observation_new_step_4")
     * @Method({"GET", "POST"})
     */
    public function stepFor(
        Request $request,
        SessionInterface $session,
        TaxrefRepository $taxref,
        ObservationRepository $observation,
        int $id = null)
    {
        if (!$request->isXmlHttpRequest()) {
            $session->set('step', '4');
            $observation = $observation->find($session->get('obsId'));

            if ($id) {
                $observation->getBird()->setTaxref($taxref->find($id));
                $observation->setUser($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($observation);
                $em->flush();
                return $this->redirectToRoute('observation_show', ['id' => $observation->getId()]);
            }

            $posts = $taxref->findByFrType();
            $options = [
                'page' => [
                    "subtitle" => "étape 4 - choix de l'oiseau"
                ],
                'form' => [
                    "include_back_btn" => true,
                    "back_btn_path"    => "observation_new_step_3"
                ],
                'map' => [
                    "address" => $observation->getLocation()->getAddress()
                ]
            ];
            return $this->render('admin/observation/new.html.twig', compact('posts', 'options'));

        }

    }

    /**
     * @Route("/{id}/edit",requirements={"id": "\d+"}, name="admin_observation_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Observation $observation)
    {
      $form = $this->createForm(ObservationType::class, $observation);
      $options = [
          'page' => [
              "title"    => "Edition de l'observation",
              "subtitle" => "Obs ID : " . $observation->getId(),
          ],
          'form' => [
              "action"           => "edit",
              "button_label"     => "Modifier",
              "include_back_btn" => true,
              "back_btn_path"    => "admin_observation_index",
              "back_btn_label"   => " ",
          ],
          'map' => [
              "address" => $observation->getLocation()->getAddress()
          ]
      ];

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($observation);
        $em->flush();
        return $this->redirectToRoute('admin_observation_index');
      }

      return $this->render('admin/observation/edit.html.twig',[
        'form' => $form->createView(),
        'options' => $options
      ]);
    }

    /**
     * @Route("cancel/{redirectTo}", name="observation_cancel")
     */
    public function cancel(SessionInterface $session, $redirectTo = 'home')
    {
        $session->remove('observation');
        $session->remove('step');
        return $this->redirectToRoute($redirectTo);
    }



}
