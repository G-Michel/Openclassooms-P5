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
            $posts = $observation->findObservationsWithLimit();
            return $this->render('observation/index.html.twig', compact('posts'));
        }

        $offset      = $request->query->get('o', '');
        $foundTaxref = $observation->findObservationsWithLimit($offset);
        $results     = [];
        foreach ($foundTaxref as $k => $observation) {
            $results[$k] = [
                'page'         => 'observation',
                'id'           => htmlspecialchars($observation->getId()),
                'lbNomType'    => htmlspecialchars($observation->getBird()->getTaxref()->getLbNomType()),
                'lbAuteurType' => trim(htmlspecialchars($observation->getBird()->getTaxref()->getLbAuteurType()),'()'),
                'nomVernType'  => htmlspecialchars($observation->getBird()->getTaxref()->getNomVernType()),
                'user'         => htmlspecialchars($observation->getUser()->getName()) . " " . htmlspecialchars($observation->getUser()->getSurname()) ,
                'userUrl'      => htmlspecialchars($observation->getUser()->getPicture()->getUrl()),
                'userAlt'      => htmlspecialchars($observation->getUser()->getPicture()->getAlt()),
                'url'          => $observation->getBird()->getTaxref()->getPicture() ? htmlspecialchars($observation->getBird()->getTaxref()->getPicture()->getUrl()): null,
                'alt'          => $observation->getBird()->getTaxref()->getPicture() ? htmlspecialchars($observation->getBird()->getTaxref()->getPicture()->getAlt()): null,
                'ago'          => $observation->getAgo(date_format($observation->getdateObs(), 'Y-m-d H:i:s'))
            ];
            if ($results[$k]['url']) {
                $results[$k]['backgroundTable'] = '';
                $results[$k]['btnColor'] = 'btn-secondary';
            } else {
                $backgroundTable = ['table-info', 'table-danger','','','','',''];
                $results[$k]['backgroundTable'] = $backgroundTable[array_rand($backgroundTable,1)];
                if ($backgroundTable === '') {
                    $results[$k]['btnColor'] = 'btn-secondary';
                } else {
                    $results[$k]['btnColor'] = 'btn-white';
                }
            }
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