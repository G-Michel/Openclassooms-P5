<?php

namespace App\Controller;

use App\Entity\Observation;
use App\Repository\TaxrefRepository;
use App\Repository\ObservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/observation")
 */
class ObservationController extends Controller
{
    /**
     * @Route("s/", name="observation_index")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function index(Request $request, ObservationRepository $observation): Response
    {
        if (!$request->isXmlHttpRequest()) {
            $posts = $observation->findObservationsWithLimit(100);
            $options = [
              'page' => [
                'title' => 'Observations',
                'subtitle' => 'Les observations de tous les membres'
              ]
            ];
            return $this->render('observation/index.html.twig', compact('posts', 'options'));
        }
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, name="observation_show")
     * @Method("GET")
     */
    public function show(Observation $observation,SessionInterface $session): Response
    {
        $options = [];
        if ($session->get('obsId')) {
            $session->remove('obsId');
            $session->remove('step');
            $options = [
                'page' => [
                    "include_newId_btn" => true
                ],
            ];
        }
        $bird = $observation->getBird();
        $taxref = $bird->getTaxref();
        return $this->render('observation/show.html.twig', [
            'post' => $taxref,
            'observation' => $observation,
            'options' => $options,
        ]);
    }

}