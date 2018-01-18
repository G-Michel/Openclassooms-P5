<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Bird;
use App\Entity\Taxref;
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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TestController extends Controller
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


        return $this->render('test/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/test/form/signIn", name="login")
     */
    public function signIn(Request $request)
    {
         if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
      return $this->redirectToRoute('home');
        }

        $authenticationUtils = $this->get('security.authentication_utils');



        $user = new User();
        $form = $this->createForm(SignInType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # code...
        }

        /*return $this->render('test/login.html.twig', [
            'form' => $form->createView(),
        ]);*/

        return $this->render('test/login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    /**
     * @Route("/test/observe/stepOne", name="observe_first_step")
     */
    public function stepOne(Request $request, SessionInterface $session)
    {
        $observation = new Observation();
        $form = $this->createForm(ObserveBirdLocationType::class, $observation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('observation', $observation);
            return $this->redirectToRoute('step_two');
            // return $this->forward('App\Contrtest\TestController::stepTwo', compact('observation'));
        }

        return $this->render('test/observe.html.twig', [
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

        return $this->render('test/form.html.twig', [
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

        return $this->render('test/form.html.twig', [
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

        return $this->render('test/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/test/", name="home")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
	public function homePage()
	{
        return $this->render('test/home.html.twig');
    }

    /**
     * @Route("/test/contact", name="contact")
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
     * @Route("/test/taxref",defaults={"page": "1", "_format"="html"}, name="listingTaxref")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function listingTaxref(Request $request, int $page, string $_format, TaxrefRepository $taxref): Response
    {
        if (!$request->isXmlHttpRequest()) {

            $results = $taxref->findByFrType();
            return $this->render('test/listing.html.twig', ['posts' => $results]);
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
     * @Route("/test/taxref/{slug}",name="showTaxref")
     * @Method("GET")
     */
    public function showTaxref(Taxref $taxref): Response
    {
        return $this->render('test/detail.html.twig', ['post' => $taxref]);
    }

    /**
     * @Route("/test/les-observations", name="listingObservations")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function listingObservations(Request $request, ObservationRepository $observation): Response
    {
        if (!$request->isXmlHttpRequest()) {
            $results = $observation->findByStatus(100);
            return $this->render('test/listing.html.twig', ['posts' => $results]);
        }
    }

    /**
     * @Route("/test/observation/{id}",name="showObservation")
     * @Method("GET")
     */
    public function showObservation(Observation $observation): Response
    {
        $bird = $observation->getBird();
        $taxref = $bird->getTaxref();
        return $this->render('test/detail.html.twig', [
            'post' => $taxref,
            'observation' => $observation,
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
