<?php

namespace App\Controller;

use App\Entity\Bird;
use App\Entity\Observation;
use App\Repository\BirdRepository;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class GmapApiController extends Controller
{
    /**
     * @Route("/json/taxref/{taxref}", name="jsonTaxref")
     * @Method("GET")
     */
    public function jsonTaxref(Request $request, $taxref, BirdRepository $bird): Response
    {
        if (!$request->isXmlHttpRequest()) {
        }

        $foundBirds = $bird->findBy(["taxref"=>$taxref]);


        $results = [
            "type"     => "FeatureCollection",
            "features" => []
        ];
        foreach ($foundBirds as $bird) {
            $results["features"][] = [
                "type"     => "Feature",
                "geometry" => [
                    "type"        => "Point",
                    "coordinates" => [
                        floatval($bird->getObservation()->getLocation()->getGpsX()),
                        floatval($bird->getObservation()->getLocation()->getGpsY())]
                ],
                "properties" => [
                    "observation" => $bird->getObservation()->getId(),
                    "picture"     => [
                        "url" => $bird->getObservation()->getUser()->getPicture()->getUrl()
                    ]
                ],

            ];
        }

        return $this->json($results);
    }

    /**
     * @Route("/json/observation/{id}", name="jsonObservation")
     * @Method("GET")
     */
    public function jsonObservation(Request $request, Observation $observation): Response
    {
        if (!$request->isXmlHttpRequest()) {

        }

        // $foundBirds = $bird->findBy(["taxref"=>$taxref]);

        $result = [
            "type"     => "Feature",
            "geometry" => [
                "type"        => "Point",
                "coordinates" => [
                    floatval($observation->getLocation()->getGpsX()),
                    floatval($observation->getLocation()->getGpsY())]
            ],
            "properties" => [
                "observation" => $observation->getId(),
                "picture"     => [
                    "url" => $observation->getUser()->getPicture()->getUrl()
                ]
            ],
        ];

        return $this->json($result);
    }


}