<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Oauth;
use App\Entity\Picture;
use App\Entity\Auth;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\ResetPasswordType;
use App\Form\LostPasswordType;

use App\Service\OauthLoginHandler;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class UserManagementController extends Controller
{
    /**
     * @Route("/signUp", name="signUp")
     */
    public function signUp(Request $request, UserPasswordEncoderInterface $passwordEncoder, OauthLoginHandler $oauthHandler)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('admin_home');
        }
        $user = new User();
        $auth = new Auth();
        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        //SignUp with Oauth services
        $oauthHandler->initOauthProvider('google');
        $glink= $oauthHandler->getAuthLink($this->generateUrl('signUp', array('service'=>"google"), UrlGeneratorInterface::ABSOLUTE_URL));

        $serviceProvider = $request->query->get('service');
        if($serviceProvider == 'google' || $serviceProvider == 'facebook')
        {
            if ($serviceProvider == 'facebook')
            {
                $oauthHandler->initOauthProvider('facebook');
            }

                if ($oauthHandler->grantAuthorisation())
                {
                    $oauthHandler->hydrateWithUserInfos($user);

                    //Persist and flush
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    try {
                       $em->flush();
                    } catch (UniqueConstraintViolationException $e) {
                        $this->addFlash('flashError',"Erreur lors de l'inscription dans la base de donnée");
                        return $this->redirectToRoute('signUp');
                    }

                     return $this->render('security/registerComfirm.html.twig',array(
                    'message' => array(
                        'inscription complétée',
                        'Vous pouvez maintenant vous connecter')));
                }
                else
                {
                    $this->addFlash('flashError',"Accés non autorisé par le fourniseur externe");
                    return $this->redirectToRoute('signUp');
                }
        }

        $oauthHandler->initOauthProvider('facebook');
        $flink= $oauthHandler->getAuthLink($this->generateUrl('signUp', array('service' =>"facebook"), UrlGeneratorInterface::ABSOLUTE_URL));


        //Handles standard signup
        if ($form->isSubmitted() && $form->isValid())

        {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->eraseCredentials();
            $user->setPassword($password);
            $user->setRoles(array('ROLE_USER'));
            $user->setAuth($auth);
            $user->setIsActive(false);
            $user->getAuth()->setComfirmedToken(uniqid('NAO_'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Email comfirm with token
            $message = (new \Swift_Message("Merci pour votre inscription"))
                ->setFrom('Openclassroom5pteam@smtp.openclass-cours.ovh')
                ->setTo($user->getMail())
                ->setBody(
                    $this->renderView(
                    'mails/comfirmMail.html.twig',
                array('user' => $user)),'text/html');
            $this->get('mailer')->send($message);

            return $this->render('security/registerComfirm.html.twig',array(
                'message' => array(
                    'inscription complétée',
                    'vous allez recevoir un mail pour confirmer votre inscription')));
        }

        // SIGNUP PAGE RENDERING
        return $this->render('security/register.html.twig', [
            'googleRegister' => $glink,
            'facebookRegister' => $flink,
            'form' => $form->createView(),
            'message' => array(
                'Inscription',
                'Veuillez remplir les champs pour vous inscrire')]);
    }

    /**
     * @Route("/comfirmMail", name="comfirmMail")
     */
    public function comfirmMail(Request $request)
    {
        $username = $request->query->get('username');
        $token = $request->query->get('token');
        // find user with the token put on parameters
        $user = $this->getDoctrine()->getRepository(User::class)->tokenComfirm($token);
        if($user== null)
        {
            return $this->render('security/registerComfirm.html.twig',array(
                'message' => array("Erreur d'activation",
                    "Token incorrect")));
        }
        else if ($user->getAuth()->getComfirmedAt()== NULL)
        {
            //Active user account
            $user->setIsActive(1);
            $user->getAuth()->setComfirmedAt(new \DateTime('NOW'));
            $user->getAuth()->setComfirmedToken(NULL);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->render('security/registerComfirm.html.twig',array(
                'message' => array('Addresse Mail confirmée',
                    'Vous pouvez maintenant vous connecter')));
        }
        else
        {
            return $this->render('security/registerComfirm.html.twig',array(
                'message' => array("Erreur d'activation",
                    "Compte déja activé")));
        }
    }



    /**
     * @Route("/signIn", name="login")
     */
    public function signIn(Request $request, AuthenticationUtils $authUtils, OauthLoginHandler $oauthHandler)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('admin_home');
        }

        //LOGIN WITH OAUTH SERVICES
        $pbkdPasswordEncoder = new Pbkdf2PasswordEncoder();

        $oauthHandler->initOauthProvider('google');
        $glink= $oauthHandler->getAuthLink($this->generateUrl('login', array('service'=>"google"), UrlGeneratorInterface::ABSOLUTE_URL));

        $serviceProvider = $request->query->get('service');
        if($serviceProvider == 'google' || $serviceProvider == 'facebook')
        {
            if ($serviceProvider == 'facebook')
            {
                $oauthHandler->initOauthProvider('facebook');
            }

            if ($oauthHandler->grantAuthorisation())
            {
                $userInfo = $oauthHandler->getUserinfos();
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array(
                'mail' =>$userInfo['email']));

                $encodedSub= $pbkdPasswordEncoder->encodePassword($userInfo['id'],"OPC-P5");

                if ($user->getOAuthUserID() == $encodedSub )
                {
                    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                    $this->get('security.token_storage')->setToken($token);
                    $this->get('session')->set('_security_main', serialize($token));
                    $event = new InteractiveLoginEvent($request, $token);
                    $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
                    return $this->redirectToRoute('home');
                }
                else
                {
                    $this->addFlash('flashError',"Les informations de connection ne correspondent pas");
                    return $this->redirectToRoute('login');
                }
            }
            else
            {
                $this->addFlash('flashError',"Erreur d'authentification");
                return $this->redirectToRoute('login');
            }
        }

        $oauthHandler->initOauthProvider('facebook');
        $flink= $oauthHandler->getAuthLink($this->generateUrl('login', array('service' =>"facebook"), UrlGeneratorInterface::ABSOLUTE_URL));

        //FOR REGULAR LOGIN
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        $user = new User();
        $form = $this->createForm(SignInType::class);

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'flink'        => $flink,
            'glink'         => $glink
        ));
    }

    /**
     * @Route("/lostPassword", name="lostPassword")
     */
    public function lostPassword(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('home');
        }
        $email = array('email' => '');
        $form = $this->createForm(LostPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email= $form->getData();
            if ($email != null)
            {
                $repository = $this->getDoctrine()->getRepository(User::class);
                if(($user = $repository->findOneBy(['mail' => $email['email']]))!=null)
                {
                    $user->getAuth()->setResetToken(uniqid('NAO_'));
                    $user->getAuth()->setResetAt(new \DateTime('NOW'));
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    // Email with token
                    $message = (new \Swift_Message("Réinitialisation de votre mot de passe"))
                        ->setFrom('Openclassroom5pteam@smtp.openclass-cours.ovh')
                        ->setTo($user->getMail())
                        ->setBody(
                            $this->renderView(
                            'mails/resetPasswordMail.html.twig',
                            array('user' => $user)),'text/html');
                    $this->get('mailer')->send($message);

                    return $this->render('security/registerComfirm.html.twig',array(
                        'message' => array('Réinitialisation Mot de Passe',
                        'Un mail vous a été envoyé pour réinitialiser votre mot de passe')));
                }
            }
            else
            {
                return $this->render('security/registerComfirm.html.twig',array(
                        'message' => array('Erreur',
                        'Aucune addresse mail trouvée')));
            }
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'message' => array(
                'Perte de mot de passe',
                'Veuillez entrer votre addresse mail')]);
    }

    /**
     * @Route("/resetPassword", name="resetPassword")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('home');
        }
        $username = $request->query->get('username');
        $token = $request->query->get('token');
        // find user with the token put on parameters
        $user = $this->getDoctrine()->getRepository(User::class)->tokenComfirm($token);

        if($user== null)
        {
            return $this->render('security/registerComfirm.html.twig',array(
                'message' => array("Erreur Réinitialisation mot de passe",
                    "Token incorrect")));
        }
        //check if the token is not outdated
        else if ($user->getAuth()->getResetAt()->diff(new \DateTime('NOW'))->format('%d')<3)
        {
            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $plainPassword = $form->getData();
                $password = $passwordEncoder->encodePassword($user, $plainPassword['plainPassword']);
                unset($plainPassword);
                $user->setPassword($password);
                $user->getAuth()->setResetToken(NULL);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->render('security/registerComfirm.html.twig',array(
                'message' => array('mot de passe changé',
                    'Vous pouvez maintenant vous connecter avec votre nouveau mot de passe')));
            }

            return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'message' => array(
                'Réinitialisation du mot de passe',
                'Veuillez entrer le nouveau mot de passe')]);
        }
        else
        {
            return $this->render('security/registerComfirm.html.twig',array(
                        'message' => array('Erreur Réinitialisation Mot de Passe',
                        'délai dépassé veuillez faire à nouveau la demande')));
        }

    }


}