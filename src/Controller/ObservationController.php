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
            return $this->render('observation/index.html.twig', compact('posts'));
        }

        $offset      = $request->query->get('o', '');
        $foundTaxref = $observation->findObservationsWithLimit($offset);
        $results     = [];
        foreach ($foundTaxref as $observation) {
            $results[] = [
                'page'         => 'observation',
                'reignType'    => htmlspecialchars($observation->getBird()->getTaxref()->getReignType()),
                'lbNomType'    => htmlspecialchars($observation->getBird()->getTaxref()->getLbNomType()),
                'lbAuteurType' => trim(htmlspecialchars($observation->getBird()->getTaxref()->getLbAuteurType()),'()'),
                'nomVernType'  => htmlspecialchars($observation->getBird()->getTaxref()->getNomVernType()),
                'slug'         => htmlspecialchars($observation->getBird()->getTaxref()->getSlug()),
                'phylumType'   => htmlspecialchars($observation->getBird()->getTaxref()->getPhylumType()),
                'classType'    => htmlspecialchars($observation->getBird()->getTaxref()->getClassType()),
                'url'          => $observation->getBird()->getTaxref()->getPicture() ? htmlspecialchars($observation->getBird()->getTaxref()->getPicture()->getUrl()): null,
                'alt'          => $observation->getBird()->getTaxref()->getPicture() ? htmlspecialchars($observation->getBird()->getTaxref()->getPicture()->getAlt()): null
            ];
        }

        return $this->json($results);

    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, name="observation_show")
     * @Method("GET")
     */
    public function show(Observation $observation): Response
    {
        $taxref = $observation->getBird()->getTaxref();
        return $this->render('observation/show.html.twig',compact('taxref', 'observation'));
    }

}