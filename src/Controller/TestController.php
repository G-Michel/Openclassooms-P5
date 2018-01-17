<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Auth;
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
use App\Repository\TaxrefRepository;
use App\Form\ObserveBirdLocationType;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class TestController extends Controller
{
    /**
     * @Route("/test/form/signUp")
     */
    public function signUp(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $auth = new Auth();
        $form = $this->createForm(SignUpType::class, $user);

        $form->handleRequest($request);

        //Handles signup 
        if ($form->isSubmitted() && $form->isValid()) 
        {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(array('ROLE_USER'));
            $user->setAuth($auth);
            $user->setIsActive(false);
            $user->getAuth()->setComfirmedToken(uniqid('NAO_'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            // Email 
            $message = (new \Swift_Message("Merci pour votre inscription"))
                ->setFrom('Openclassroom5pteam@smtp.openclass-cours.ovh')
                ->setTo($user->getMail())
                ->setBody(
                    $this->renderView(
                    'mails/comfirmMail.html.twig',
                array('user' => $user)
            ),'text/html');

            $this->get('mailer')->send($message);

            return $this->render('test/resgisterComfirm.html.twig',array(
                'message' => array('inscription complétée',
                    'vous allez recevoir un mail pour confirmer votre inscription ')));
        }


        return $this->render('test/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/test/form/comfirmMail")
     */
    public function comfirmMail(Request $request)
    {
        $username = $request->query->get('username');
        $token = $request->query->get('token');
        // find the user with the token put on parameters
        $user = $this->getDoctrine()->getRepository(User::class)->tokenComfirm($username,$token);

        if($user== null)
        {
            return $this->render('test/resgisterComfirm.html.twig',array(
                'message' => array('problème confirmation mail',
                    'la fin du monde')));

        }
        else
        {
            $user->setIsActive(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->render('test/resgisterComfirm.html.twig',array(
                'message' => array('Addresse Mail confirmée',
                    'Vous pouvez maintenant vous connecter')));

        }
        

    }


    /**
     * @Route("/test/form/signIn", name="login")
     */
    public function signIn(Request $request, AuthenticationUtils $authUtils)
    {
         if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
      return $this->redirectToRoute('home');
        }

        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();


        $user = new User();
        $form = $this->createForm(SignInType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            

        }


        return $this->render('test/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
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
     * @Route("/test/listing/taxref",defaults={"page": "1", "_format"="html"}, name="listingTaxref")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function listingTaxref(Request $request, int $page, string $_format, TaxrefRepository $taxref): Response
    {
        if (!$request->isXmlHttpRequest()) {

            $result = $taxref->findBy([],['picture' => 'DESC'],100);
            return $this->render('test/list.html.twig', ['posts' => $result]);
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
     * @Route("/test/listing/mes-observations",defaults={"page": "1", "_format"="html"}, name="listingObservations")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function listingObservation(Request $request, int $page, string $_format, TaxrefRepository $taxref): Response
    {
        if (!$request->isXmlHttpRequest()) {

            $result = $taxref->findBy([],['picture' => 'DESC'],100);
            return $this->render('test/list.html.twig', ['posts' => $result]);
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
