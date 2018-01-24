<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Bird;
use App\Entity\Taxref;
use App\Entity\Auth;
use App\Entity\Article;
use App\Form\SignInType;
use App\Entity\Location;
use App\Form\SignUpType;
use App\Form\ArticleType;
use App\Form\ContactType;
use App\Entity\Observation;
use App\Form\ObservationType;
use App\Form\ObserveBirdDetailType;
use App\Form\ObserveBirdMomentType;

use App\Repository\TaxrefRepository;

use App\Form\ObserveBirdLocationType;
use App\Repository\LocationRepository;

use App\Repository\ObservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class TestController extends Controller
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
        return $this->render('test/home.html.twig');
    }

    /**
     * @Route("/observe", name="observe")
     * @Route("{action}/observe/{redirect}", name="observe_action")
     * @IsGranted("ROLE_USER")
     */
    public function observation(SessionInterface $session, $action = null, $redirect = 'home')
    {
        if ($action && $action == "above") {
            $session->remove('observation');
            $session->remove('step');
            return $this->redirectToRoute($redirect);
        }
        return $this->redirectToRoute('observe_step_1');
    }

    /**
     * @Route("/observe/lieu", name="observe_step_1")
     * @IsGranted("ROLE_USER")
     */
    public function stepOne(Request $request, SessionInterface $session)
    {
        $session->set('step','1');
        $observation = new Observation();
        $form = $this->createForm(ObserveBirdLocationType::class, $observation);
        $options = [
            'page' => [
                "title" => "étape 1 - lieu de l'observation"
            ],
            'form' => [
                "include_back_btn" => false,
                "back_btn_path"    => null
            ]
        ];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('observation', $observation);
            return $this->redirectToRoute('observe_step_2');
        }

        return $this->render('test/observe.html.twig', [
            'options' => $options,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/observe/moment", name="observe_step_2")
     */
    public function stepTwo(Request $request, SessionInterface $session)
    {
        $session->set('step','2');
        $observation = $session->get('observation');;
        $form = $this->createForm(ObserveBirdMomentType::class, $observation);
        $options = [
            'page' => [
                "title" => "étape 2 - moment de l'observation",
            ],
            'form' => [
                "include_back_btn" => true,
                "back_btn_path"    => "observe_step_1"
            ],
            'map' => [
                "address" => $observation->getLocation()->getAddress()
            ]
        ];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('observe_step_3');
        }

        return $this->render('test/observe.html.twig', [
            'options' => $options,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/observe/oiseau", name="observe_step_3")
     * @IsGranted("ROLE_USER")
     */
    public function stepThree(Request $request, SessionInterface $session)
    {

        $session->set('step','3');
        $observation = $session->get('observation');
        $form = $this->createForm(ObserveBirdDetailType::class, $observation);
        $options = [
            'page' => [
                "title" => "étape 3 - détail de l'observation"
            ],
            'form' => [
                "include_back_btn" => true,
                "back_btn_path"    => "observe_step_2"
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

            return $this->forward('App\Controller\TestController::stepFor');
        }

        return $this->render('test/observe.html.twig', [
            'options' => $options,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/observe/liste", name="observe_step_4")
     * @Route("/observe/liste/{id}", requirements={"id": "[1-9]\d*"}, name="observe_step_4_id")
     * @IsGranted("ROLE_USER")
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
                return $this->redirectToRoute('showObservation', ['id' => $observation->getId()]);
            }

            $posts = $taxref->findByFrType();
            $options = [
                'page' => [
                    "title" => "étape 4 - choix de l'oiseau"
                ],
                'form' => [
                    "include_back_btn" => true,
                    "back_btn_path"    => "observe_step_3"
                ],
                'map' => [
                    "address" => $observation->getLocation()->getAddress()
                ]
            ];
            return $this->render('test/observe.html.twig', compact('posts', 'options'));

        }

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

        return $this->render('test/contact.html.twig', [
            'form' => $form->createView(),
        ]);
	}

    /**
     * @Route("/taxref",defaults={"page": "1", "_format"="html"}, name="listingTaxref")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function listingTaxref(Request $request, int $page, string $_format, TaxrefRepository $taxref): Response
    {
        if (!$request->isXmlHttpRequest()) {

            $posts = $taxref->findByFrType();
            $page = [
                'title' => 'Taxref',
                'subtitle' => 'Le référentiel taxonomique taxref'
            ];
            return $this->render('test/listing.html.twig', compact('posts', 'page'));

        }

        $rawQuery = $request->query->get('q', '');
        $query = $this->sanitizeSearchQuery($rawQuery);
        $searchTerms = $this->extractSearchTerms($query);
        $termsLighting = $this->lightingSearchTerms($searchTerms);
        $limit = $request->query->get('l', 30);
        $foundPosts = $taxref->findBySearchQuery($searchTerms, $limit);

        $results = [];
        foreach ($foundPosts as $post) {
            // dump($post);die();
            $results[] = [
                'nomVernType' => str_ireplace($searchTerms,$termsLighting,htmlspecialchars($post->getNomVernType())),
                'phylumType' => htmlspecialchars($post->getPhylumType()),
                'classType' => htmlspecialchars($post->getClassType()),
                'url' => $post->getPicture() ? htmlspecialchars($post->getPicture()->getUrl()):'',
                'alt' => $post->getPicture() ? htmlspecialchars($post->getPicture()->getAlt()):''
            ];
        }

        return $this->json($results);
    }

    /**
     * @Route("/taxref/{slug}",name="showTaxref")
     * @Method("GET")
     */
    public function showTaxref(Taxref $taxref): Response
    {
        return $this->render('test/detail.html.twig', ['post' => $taxref]);
    }

    /**
     * @Route("/les-observations", name="listingObservations")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function listingObservations(Request $request, ObservationRepository $observation): Response
    {
        if (!$request->isXmlHttpRequest()) {
            $posts = $observation->findByStatus(100);
            $page = [
                'title' => 'Observations',
                'subtitle' => 'Les observations de tous les membres'
            ];
            return $this->render('test/listing.html.twig', compact('posts', 'page'));
        }
    }

    /**
     * @Route("/observation/{id}",name="showObservation")
     * @Method("GET")
     */
    public function showObservation(Observation $observation,SessionInterface $session): Response
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
        return $this->render('test/detail.html.twig', [
            'post' => $taxref,
            'observation' => $observation,
            'options' => $options,
        ]);
    }


    /**
     * Removes all non-alphanumeric characters except whitespaces.
     */
    private function sanitizeSearchQuery(string $query): string
    {
        return preg_replace('/[^[:alnum:] ]/', '', trim(preg_replace('/[[:space:]]+/', ' ', $query)));
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(explode(' ', mb_strtolower($searchQuery)));

        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function lightingSearchTerms(array $searchTerms): array
    {
        $bgs = ['bg-primary','bg-secondary','bg-success','bg-danger','bg-info'];
        return array_map(function($term, $bg) {
            $bg = $bg ?: 'bg-primary';
            return '<span class="'.$bg.' text-white">' . $term . '</span>';

        }, $searchTerms,$bgs);

    }
}
