<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\RequestStack;

use App\Entity\User;
use App\Entity\Picture;
use App\Entity\Auth;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;

class OauthLoginHandler
{	
	/**
	*
	*
	*/
	private $requestStack;

	/**
	*
	*
	*/
	private $container;

	/**
	*
	*
	*/
	private $clientKey;

	/**
	*
	*
	*/
	private $secretKey;

	/**
	*
	*
	*/
	private $providerName;

	/**
	*
	*
	*/
	private $oauthObject;

	/**
	*
	*
	*/
	private $token;


	public function __construct(RequestStack $requestStack, Container $container, UserPasswordEncoderInterface $userPasswordEncoder)
	{
		$this->requestStack = $requestStack;
		$this->container = $container;
		$this->userPasswordEncoder = $userPasswordEncoder;
	}

	/**
	*
	*
	*/
	public function initOauthProvider($providerName)
	{
		if(isset($this->token))unset($this->token);

		if ($providerName == 'facebook')
		{
			$this->providerName = $providerName;

			//Get oauth keys
			$this->clientKey= $this->container->getParameter('appId');
			$this->secretKey= $this->container->getParameter('appSecret');

			//init facebook object
			$this->oauthObject =  new \Facebook\Facebook(array(
				'app_id' => $this->clientKey,
				'app_secret' => $this->secretKey,
				'default_graph_version' => 'v2.11',
			));

		}
		else if ($providerName == 'google')
		{
			$this->providerName = $providerName;

			//Get oauth keys
			$this->clientKey= $this->container->getParameter('gclientId');
			$this->secretKey= $this->container->getParameter('gclientSecret');

			//Init Google object
			$this->oauthObject = new \Google_Client();
			$this->oauthObject->setApplicationName('OPC P5');
			$this->oauthObject->setClientId($this->clientKey);
			$this->oauthObject->setClientSecret($this->secretKey);
			$this->oauthObject->addScope('https://www.googleapis.com/auth/plus.me');
			$this->oauthObject->addScope("https://www.googleapis.com/auth/userinfo.profile");
			$this->oauthObject->addScope("https://www.googleapis.com/auth/userinfo.email");
			//$this->googleServiceObject = new \Google_Service_Plus($this->google);

		}
		else
		{
			return "Error: provider not available";
		}
	}

	/**
	*
	*/
	public function getAuthLink($url)//GET login link
	{
		if ($this->providerName == 'facebook')
		{

			$helper = $this->oauthObject->getRedirectLoginHelper();
			$permissions = ['email','public_profile'];
			$loginUrl = $helper->getLoginUrl($url, $permissions);

			return htmlspecialchars($loginUrl);
		}
		else if ($this->providerName == 'google')
		{
			$this->oauthObject->setRedirectUri($url);
			$glink = $this->oauthObject->createAuthUrl();
			$loginUrl = filter_var($glink, FILTER_SANITIZE_URL);

			return $loginUrl;
		}
		else
		{

		}
	}

	public function grantAuthorisation()//WAY TO GET THE ACCESS TOKEN
	{
		if ($this->providerName == "facebook")
		{
			$fb= $this->oauthObject;
			$helper = $fb->getRedirectLoginHelper();

			try {
			  $accessToken = $helper->getAccessToken();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
			  // When Graph returns an error
			  echo 'Graph returned an error: ' . $e->getMessage();
			  exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			  // When validation fails or other local issues
			  echo 'Facebook SDK returned an error: ' . $e->getMessage();
			  exit;
			}

			if (! isset($accessToken)) {
			  if ($helper->getError()) {
			    header('HTTP/1.0 401 Unauthorized');
			    echo "Error: " . $helper->getError() . "\n";
			    echo "Error Code: " . $helper->getErrorCode() . "\n";
			    echo "Error Reason: " . $helper->getErrorReason() . "\n";
			    echo "Error Description: " . $helper->getErrorDescription() . "\n";
			  } else {
			    header('HTTP/1.0 400 Bad Request');
			    echo 'Bad request';
			  }
			  return false;
			}
			else 
			{
				$this->token = $accessToken;
				return true;
			}
		}
		else if ($this->providerName == "google")
		{
			$request = $this->requestStack->getCurrentRequest();
			$this->oauthObject->setAccessToken($request->query->get("code"));
			$token = $this->oauthObject->authenticate($request->query->get("code"));
			if (isset($token)) 
			{
				$this->token= $token;
				return true;
			}
			else return false;	
		}
		else return false;
	}

	public function getUserInfos()
	{
		if ($this->providerName == 'google')
		{
			$ticket = $this->oauthObject->verifyIdToken($this->token['id_token']);

			
			if ($ticket)
			{
				$userInfo = array(
					'picture'=>$ticket['picture'],
					'username'=>strtolower($ticket['name']),
					'name'=>$ticket['given_name'],
					'famillyName'=>$ticket['family_name'],
					'email'=>$ticket['email'],
					'id'=>$ticket['sub'],
				);

				return $userInfo;	
			}
			else return false;
		}
		else if ($this->providerName == 'facebook')
		{
			$data  = $this->oauthObject->get('/me?fields=id,name,email,first_name,last_name,picture', $this->token);
			$ticket =  $data->getGraphUser();
			$userInfo = array(
					'picture'=>$ticket->getPicture()->getUrl(),
					'username'=>strtolower($ticket->getName()),
					'name'=>$ticket->getFirstName(),
					'famillyName'=>$ticket->getLastName(),
					'email'=>$ticket->getEmail(),
					'id'=>$ticket->getId(),
				);
			return $userInfo;
		}
	}

	public function hydrateWithUserInfos($user)
	{
				$userInfo = $this->getUserInfos();
        		$pbkdPasswordEncoder = new Pbkdf2PasswordEncoder();

                $user->setName($userInfo['name']);
                $user->setSurname($userInfo['famillyName']);
                $user->setUsername(strtolower($userInfo['username']));
                $user->setMail(strtolower($userInfo['email']));
                
                //Encoded OAUTH USER ID
                $encodedSub= $pbkdPasswordEncoder->encodePassword($userInfo['id'],"OPC-P5");
                $user->setOAuthUserId($encodedSub);

                $user->setOAuthProvider(1);
                $user->setRoles(['ROLE_USER']);
                $user->setIsActive(1);
                $generatedPassword = openssl_random_pseudo_bytes(40);
                $generatedPaddword = uniqid(uniqid(),true). $generatedPassword;
                $encodedPassword= $this->userPasswordEncoder->encodePassword($user,$generatedPassword);
                $user->setPassword($encodedPassword);
                $user->setSalt('');
                $auth = new Auth();
                $user->setAuth($auth);
                //avatar pic
                $picture = new Picture();
                $picture->setAlt('avatar');
                $picture->setUrl($userInfo['picture']);
                $user->setPicture($picture);
	}
}