<?php

namespace App\Controller\Admin;

use App\Entity\Observation;
use App\Entity\Picture;
use App\Entity\Notification;

use App\Form\EditProfileType;
use App\Repository\ObservationRepository;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends Controller
{
    /**
     * @Route("/admin/", name="admin_home")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
	public function homePage(Request $request, ObservationRepository $observation)
	{
		// $user = $this->get("security.token_storage")->getToken()->getUser();
		// $obsRepository = $this->getDoctrine()->getRepository(Observation::Class);
		// $userObservations = $obsRepository->findByUser(5,$user->getUsername());
    // $observationsToValid=$obsRepository->findToValid(5);
		$userObservations    = $observation->findByUser($this->getUser(),5);
		$observationsToValid = $observation->findEqualToStatus(0,5);

        return $this->render('admin/espacePerso/espacePersonnel.html.twig',array(
        	'userObservations' => $userObservations,
        	'obsToValid'       => $observationsToValid
        ));
    }


    /**
     * @Route("/admin/mesNotifications", name="admin_user_notifications")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
  public function myNotifications(Request $request, NotificationRepository $notification)
  {

    if ($request->query->get("erase") == "yes")
    {
      $em = $this->getDoctrine()->getManager();

      $notifications = $em->getRepository(Notification::class)->findUserNotifications($this->getUser()->getId());

      foreach ($notifications as $notification) $em->remove($notification);
      $em->flush();

      $this->addFlash('primary','notifications supprimées');
      return $this->redirectToRoute('admin_user_notifications');

    }

    $userNotifications = $notification->findUserNotifications($this->getUser()->getId());
        return $this->render('admin/espacePerso/mesNotifications.html.twig',array(
          'userNotifications' => $userNotifications,
        ));
    }


    /**
     * @Route("/admin/seen", name="seen_update")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
  public function seenUpdate(Request $request, NotificationRepository $notification)
  {
      if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('admin_home');
        }
        $toFlush=0;
        $seen = $request->query->get('seen');

        if ($seen == "desktop" || $seen == "mobile" )
        {
          $em = $this->getDoctrine()->getManager();
          $notifs = $this->get('session')->get("notificationUser");

          if ($notifs != null)
          {

            foreach ($notifs as $notification)
            {
              if ($notification->getSeen()==false)
              {
                $notification->setSeen(true);
                $notifdb = $this->getDoctrine()->getRepository(Notification::class)->find($notification->getId());
                $notifdb->setSeen(true);
                $toFlush++;
                $em->persist($notifdb);
              }
            }
            if ($toFlush>0)
            {
              $em->flush();
              if ($seen == "desktop") return $this->render('partials/notificationAreaNav.html.twig');
              if ($seen == "mobile") return $this->render('partials/notificationMobileNav.html.twig');
            }
            else
            {
              return new Response('nothing to flush');
            }
          }
          else
            {
              return new Response('nothing to flush');
            }
        }

  }


    /**
    * @Route("/admin/editProfil", name="edit_profil")
    * @Cache(smaxage="10")
    */
    public function editProfil(Request $request, UserPasswordEncoderInterface $encoder)
    {
      $picture = new Picture();

      $user = $this->get('security.token_storage')->getToken()->getUser();
      $form = $this->createForm(EditProfileType::class);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid())
      {
        $formData = $form->getData();

        //check if a new value has been included
        $toEdit=false;
        $valuesToChange = array("name","surname","mail");

        foreach ($valuesToChange as $value)
        {
          if ($formData[$value]!=null)
          {
            $toEdit=true;
            $method = "set".$value;
            $user->$method($formData[$value]);
          }
        }

        if ($formData['picture']->getFile() !== null)
        {
          $toEdit=true;
          $user->setPicture($formData['picture']);
        }

        // is password valid ?
        if ($formData["resetPassword"]["plainPassword"] != null && $formData["currentPassword"] != null)
        {
          $validCurrentPassword = $encoder->isPasswordValid($user,$formData["currentPassword"]);

          if ($validCurrentPassword)
          {
            $encodedPassword = $encoder->encodePassword($user,$formData["resetPassword"]['plainPassword']);
            $user->setPassword($encodedPassword);
            $toEdit=true;
          }
        }
        //flush if values entered
        if($toEdit)
        {
          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          try
          {
            $em->flush();

          } catch (Doctrine\ORM\ORMException $e)
          {
            $this->addFlash('danger',"une erreur est survenue lors du changement d'infos personnelles");
            $this->redirectToRoute('edit_profil');
          }
          $this->addFlash('success',"Vous avez bien mis à jour vos données personnelles");
          $this->redirectToRoute('edit_profil');
        }
        else
        {
              $this->addFlash('danger',"Aucunes modifications apportées");
              $this->redirectToRoute('edit_profil');
        }
      }
      return $this->render('admin/espacePerso/editProfil.html.twig',array(
        'form' => $form->createView()
      ));
    }
}


