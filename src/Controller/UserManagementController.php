<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Auth;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\ResetPasswordType;
use App\Form\LostPasswordType;
use App\Service\LoginFacebook;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserManagementController extends Controller
{
    /**
     * @Route("/signUp", name="signUp")
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
                array('user' => $user)
            ),'text/html');
            $this->get('mailer')->send($message);

            return $this->render('test/registerComfirm.html.twig',array(
                'message' => array(
                    'inscription complétée',
                    'vous allez recevoir un mail pour confirmer votre inscription')));
        }

        return $this->render('test/register.html.twig', [
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
            return $this->render('test/registerComfirm.html.twig',array(
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
            return $this->render('test/registerComfirm.html.twig',array(
                'message' => array('Addresse Mail confirmée',
                    'Vous pouvez maintenant vous connecter')));
        }
        else
        {
            return $this->render('test/registerComfirm.html.twig',array(
                'message' => array("Erreur d'activation",
                    "Compte déja activé")));
        }
    }

    /**
     * @Route("/signIn", name="login")
     */
    public function signIn(Request $request, AuthenticationUtils $authUtils)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('admin_home');
        }

        //Facebook Login tests
        //$facebook = new LoginFacebook();
        //$facebookLink = $facebook->getLoginLink($this->generateUrl('login'));


        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        $user = new User();
        $form = $this->createForm(SignInType::class);

        return $this->render('test/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error
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

                    return $this->render('test/registerComfirm.html.twig',array(
                        'message' => array('Réinitialisation Mot de Passe',
                        'Un mail vous a été envoyé pour réinitialiser votre mot de passe')));
                }
            }
            else
            {
                return $this->render('test/registerComfirm.html.twig',array(
                        'message' => array('Erreur',
                        'Aucune addresse mail trouvée')));
            }
        }
        return $this->render('test/register.html.twig', [
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
            return $this->render('test/registerComfirm.html.twig',array(
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

                return $this->render('test/registerComfirm.html.twig',array(
                'message' => array('mot de passe changé',
                    'Vous pouvez maintenant vous connecter avec votre nouveau mot de passe')));
            }

            return $this->render('test/register.html.twig', [
            'form' => $form->createView(),
            'message' => array(
                'Réinitialisation du mot de passe',
                'Veuillez entrer le nouveau mot de passe')]);
        }
        else
        {
            return $this->render('test/registerComfirm.html.twig',array(
                        'message' => array('Erreur Réinitialisation Mot de Passe',
                        'délai dépassé veuillez faire à nouveau la demande')));
        }

    }


}