<?php

namespace App\Controller\Admin;

use App\Entity\Observation;
use App\Form\ObservationType;
use App\Form\ObserveBirdDetailType;
use App\Form\ObserveBirdMomentType;
use App\Repository\TaxrefRepository;
use App\Form\ObserveBirdLocationType;
use App\Repository\ObservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/observation")
 * @Security("has_role('ROLE_USER')")
 */
class ObservationController extends Controller
{


    /**
     * Finds and displays all Observations entity of user.
     *
     * @Route("s/", name="admin_observation_index")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function index(ObservationRepository $observation)
    {
      $userObservations        = $observation->findByUser($this->getUser());
      $observationsToCheck     = $observation->findEqualToStatus(0);
      $uncommittedObservations = $observation->findLessThanOrEqualStatus(-200);

      return $this->render(
        'admin/observation/index.html.twig',
        compact('userObservations','observationsToCheck', 'uncommittedObservations')
      );
    }

    /**
     * Finds and displays a Observation entity.
     *
     * @Route("/{id}", requirements={"id": "\d+"}, name="admin_observation_show")
     * @Method("GET")
     * @Security("is_granted('show', observation)")
     */
    public function show(Observation $observation): Response
    {
        $taxref = $observation->getBird()->getTaxref();
        return $this->render('observation/show.html.twig',compact('taxref', 'observation'));
    }

    /**
     * @Route("/ajout", name="admin_observation_new")
     * @Method({"GET"})
     */
    public function new(SessionInterface $session)
    {
        return $this->redirectToRoute('admin_observation_new_step_1');
    }

    /**
     * @Route("/ajout/lieu", name="admin_observation_new_step_1")
     * @Method({"GET", "POST"})
     */
    public function stepOne(Request $request, SessionInterface $session, ObservationRepository $observation)
    {
        $session->set('step','1');
        if ($session->get('observation')) {
            $observation = $observation->find($session->get('observation'));
        } else {
            $observation = new Observation();
        }

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

            $observation->setStatus(-101);
            $em = $this->getDoctrine()->getManager();
            $em->persist($observation);
            $em->flush();
            $session->set('observation', $observation->getId());

            return $this->redirectToRoute('admin_observation_new_step_2');
        }

        return $this->render('admin/observation/new.html.twig', [
            'options' => $options,
            'form' => $form->createView(),
            'observation' => $observation
        ]);
    }

    /**
     * @Route("/ajout/moment", name="admin_observation_new_step_2")
     * @Method({"GET", "POST"})
     */
    public function stepTwo(Request $request, SessionInterface $session, ObservationRepository $observation)
    {
        $session->set('step','2');
        $observation = $observation->find($session->get('observation'));
        $form = $this->createForm(ObserveBirdMomentType::class, $observation);
        $options = [
            'page' => [
                "subtitle" => "étape 2 - moment de l'observation",
            ],
            'form' => [
                "include_back_btn" => true,
                "back_btn_path"    => "admin_observation_new_step_1"
            ],
            'map' => [
                "address" => $observation->getLocation()->getAddress()
            ]
        ];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $observation->setStatus(-102);
            $em = $this->getDoctrine()->getManager();
            $em->persist($observation);
            $em->flush();
            $session->set('observation', $observation->getId());

            return $this->redirectToRoute('admin_observation_new_step_3');
        }

        return $this->render('admin/observation/new.html.twig', [
            'options' => $options,
            'form' => $form->createView(),
            'observation' => $observation
        ]);
    }

    /**
     * @Route("/ajout/oiseau", name="admin_observation_new_step_3")
     * @Method({"GET", "POST"})
     */
    public function stepThree(Request $request, SessionInterface $session, ObservationRepository $observation)
    {

        $session->set('step','3');
        $observation = $observation->find($session->get('observation'));
        $form = $this->createForm(ObserveBirdDetailType::class, $observation);
        $options = [
            'page' => [
                "subtitle" => "étape 3 - détail de l'observation"
            ],
            'form' => [
                "include_back_btn" => true,
                "back_btn_path"    => "admin_observation_new_step_2"
            ],
            'map' => [
                "address" => $observation->getLocation()->getAddress()
            ]
        ];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $observation->setStatus(-103);
            $em = $this->getDoctrine()->getManager();
            $em->persist($observation);
            $em->flush();
            $session->set('observation', $observation->getId());

            return $this->forward('App\Controller\Admin\ObservationController::stepFor');
        }

        return $this->render('admin/observation/new.html.twig', [
            'options' => $options,
            'form' => $form->createView(),
            'observation' => $observation
        ]);
    }

    /**
     * @Route("/ajout/reference/{id}", requirements={"id": "[1-9]\d*"}, name="admin_observation_new_step_4")
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
            $observation = $observation->find($session->get('observation'));

            if ($id) {

                $observation->getBird()->setTaxref($taxref->find($id));
                $observation->setUser($this->getUser());
                $observation->setStatus(0);

                $em = $this->getDoctrine()->getManager();
                $em->persist($observation);
                $em->flush();

                $session->remove('observation');
                $session->remove('step');

                $this->addFlash('success', 'Observation ajouté avec succès !');

                return $this->redirectToRoute('admin_observation_show', ['id' => $observation->getId()]);
            }

            $posts = $taxref->findByFrType();
            $options = [
                'page' => [
                    "subtitle" => "étape 4 - choix de l'oiseau"
                ],
                'form' => [
                    "include_back_btn" => true,
                    "back_btn_path"    => "admin_observation_new_step_3"
                ],
                'map' => [
                    "address" => $observation->getLocation()->getAddress()
                ]
            ];
            return $this->render('admin/observation/new.html.twig',
                compact('posts', 'options','observation')
            );

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

        $this->addFlash('success', 'Observation modifié avec succès !');

        return $this->redirectToRoute('admin_observation_index');
      }

      return $this->render('admin/observation/edit.html.twig',[
        'form' => $form->createView(),
        'options' => $options,
        'observation' => $observation,
      ]);
    }

    /**
     * @Route("/cancel/{redirectTo}", name="observation_cancel")
     */
    public function cancel(SessionInterface $session, $redirectTo = 'home')
    {
        $session->remove('observation');
        $session->remove('step');
        return $this->redirectToRoute($redirectTo);
    }

    /**
     * Deletes an Observation entity.
     *
     * @Route("/{id}/delete", name="admin_observation_delete")
     * @Method("POST")
     * @Security("is_granted('delete', observation)")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     */
    public function delete(Request $request, Observation $observation): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_observation_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($observation);
        $em->flush();

        $this->addFlash('success', 'Observation supprimée avec succès !');

        return $this->redirectToRoute('admin_observation_index');
    }

    /**
     * Check an Observation entity.
     *
     * @Route("/{id}/check", name="admin_observation_check")
     * @Method("POST")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     */
    public function check(Request $request, Observation $observation): Response
    {
        if (!$this->isCsrfTokenValid('check', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_observation_index');
        }
        $observation->setStatus($request->request->get('status'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($observation);
        $em->flush();

        $this->addFlash('success', 'Votre vérification a bien été pris en compte !');

        return $this->redirectToRoute('admin_observation_index');
    }



}
