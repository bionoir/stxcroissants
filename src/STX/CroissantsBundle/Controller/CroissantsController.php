<?php

namespace STX\CroissantsBundle\Controller;

use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use STX\UserBundle\Entity\User;
use STX\CroissantsBundle\Entity\Friday_Subscriptions;
use Symfony\Component\HttpFoundation\Response;

class CroissantsController extends Controller
{
	
	/*
	public function homeAction($date)
	{
		$em = $this->getDoctrine()->getManager();
		 
		$user = new User();
		 
		$user = $this->getUser();
		
		if (is_null($user)) {
			$name = 'ERROR';
			$username = 'ERROR_2';
		} else {
			$name = $user->getFirstname();
			$username = $user->getUsername();
		}
		 
		// trouver le nombre de vendredi à afficher 
		 
		$daysToDisplay = $user->getDays();
		 
		$today = new DateTime();
		$forCompare = new DateTime();
		 
		if (!is_null($date)) {
			$today=DateTime::createFromFormat("dmY", $date);
		}
		 
		 
		// If it's already friday, then don't need to get next friday
		if ( date("w", $today->getTimestamp()) != 5 ) {
			$today->modify('next friday');
		}
		 
		$today->setTime(0, 0);
		 
		$nextFriday = new DateTime();
		$nextFriday = clone $today;
		 
		$repositoryFS = $this->getDoctrine()->getManager()->getRepository('STXCroissantsBundle:Friday_Subscriptions');
		$friday_subscriber = $repositoryFS->findOneBy(array('date' => $nextFriday));
		 
		$croissantsSubscribers = array();
		array_push($croissantsSubscribers, array('dateSub' => $nextFriday,
				'userSub' => $friday_subscriber));
		 
		 
		 
		$nextXFridays = array();
		array_push($nextXFridays, $nextFriday);
		 
		for ($i = 1; $i < $daysToDisplay; $i++) {
			$dayToAdd = new DateTime();
			$subscriberToAdd = new Friday_Subscriptions();
		
			$today->modify('+7 days');
			$dayToAdd = clone $today;
				
			$subscriberToAdd = $repositoryFS->findOneBy(array('date' => $dayToAdd));
		
			array_push($croissantsSubscribers, array('dateSub' => $dayToAdd,
					'userSub' => $subscriberToAdd));
			array_push($nextXFridays, $dayToAdd);
		}
		
		return $this->render('STXCroissantsBundle:Croissants:home.html.twig', array('username' => $username,
				'userDaysDiplay' => $daysToDisplay,
				'fridays' => $nextXFridays,
				'subscribers' => $croissantsSubscribers,
				'forCompare' => $forCompare,
				'calendarDate' => $date,
				'userFirstname' => $name
		));
	}
	*/
	
	
    public function homeAction($date)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$user = new User();
    	 
    	$user = $this->getUser();
    	
    	if (is_null($user)) {
    		$name = 'ERROR';
    	} else {
    		$name = $user->getFirstname();
    	}
    	
    	return $this->render('STXCroissantsBundle:Croissants:home.html.twig', array('date' => $date, 'userFirstname' => $name, 'calendarDate' => $date) );
    }
    
    
    
    public function subscriberlistAction($date)
    {
    		 
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$user = new User();
    	
    	$user = $this->getUser();
    	
    	$username = $user->getUsername();
    	
    	
    	/* trouver le nombre de vendredi à afficher */
    	
    	$daysToDisplay = $user->getDays();
    	
    	$today = new DateTime();
    	/*$today = new DateTime('2015-06-01');*/
    	$forCompare = new DateTime();
    	
    	if (!is_null($date)) {
    		$today=DateTime::createFromFormat("dmY", $date);
    	}
    	
    	
    	/* If it's already friday, then don't need to get next friday */
    	if ( date("w", $today->getTimestamp()) != 5 ) {
    		$today->modify('next friday');
    	}
    	
    	$today->setTime(0, 0);
    	
    	$nextFriday = new DateTime();
    	$nextFriday = clone $today;
    	
    	$repositoryFS = $this->getDoctrine()->getManager()->getRepository('STXCroissantsBundle:Friday_Subscriptions');
    	$friday_subscriber = $repositoryFS->findOneBy(array('date' => $nextFriday));
    	
    	$croissantsSubscribers = array();
    	array_push($croissantsSubscribers, array('dateSub' => $nextFriday,
    											'userSub' => $friday_subscriber));
    	
    	
    	$nextXFridays = array();
    	array_push($nextXFridays, $nextFriday);
    	
    	for ($i = 1; $i < $daysToDisplay; $i++) {
    		$dayToAdd = new DateTime();
    		$subscriberToAdd = new Friday_Subscriptions();

    		$today->modify('+7 days');
    		$dayToAdd = clone $today;
			
    		$subscriberToAdd = $repositoryFS->findOneBy(array('date' => $dayToAdd));
    		
    		array_push($croissantsSubscribers, array('dateSub' => $dayToAdd,
    												'userSub' => $subscriberToAdd));
    		array_push($nextXFridays, $dayToAdd);
    	}
    	
    	/* Liste des utilisateurs du site */
    	$croissantsUsers = $this->getDoctrine()
    						->getRepository('STXCroissantsBundle:Friday_Subscriptions')
    						->getUsersList();
    	
    	
        $template = $this->render('STXCroissantsBundle:Croissants:subscriberlist.html.twig', array('username' => $username,
        																			'userDaysDiplay' => $daysToDisplay,
        																			'fridays' => $nextXFridays,
        																			'subscribers' => $croissantsSubscribers,
        																			'forCompare' => $forCompare,
        																			'croissantsUsers' => $croissantsUsers
        ));
        
        return $template;
        
        /*
        $template = $this->render('STXCroissantsBundle:Croissants:subscriberlist.html.twig', array('username' => $username,
        		'userDaysDiplay' => $daysToDisplay,
        		'fridays' => $nextXFridays,
        		'subscribers' => $croissantsSubscribers,
        		'forCompare' => $forCompare
        ))->getContent();
        
        $json = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        */
    }
    
    
    
    public function subscribeCroissantAction()
    {
    	$request = $this->getRequest();
    	
    	if ($request->isXmlHttpRequest()) {
    		
    		$response = new Response();
    		
    		$dayOfSubscription = DateTime::createFromFormat("dmY", $request->request->get('date'));
    		$dayOfSubscription->setTime(0,0);
    		
    		$roleSubscription = $request->request->get('role');
    		
    		/*
    		$test = substr_replace(substr_replace($request->get('date'),'-',2,0), '-',5,0);
    		$dayOfSubscription_V1 = DateTime::createFromFormat("d-m-Y",$test);
    		$dayOfSubscription_V1->setTime(0,0);
    		*/
    		
    		$username = $request->request->get('username');
    		
    		$em = $this->getDoctrine()->getManager();
    		
    		$repositoryFS = $em->getRepository('STXCroissantsBundle:Friday_Subscriptions');
    		$friday_subscriber = $repositoryFS->findOneBy(array('date' => $dayOfSubscription));
    		
    		/**
    		 * If the subscriptions doesn't exist or the main subscriber is empty,
    		 * then add the user as subscriber
    		 *
    		 */
    		
    		if (is_null($friday_subscriber)) {
    			
    			$userManager = $this->get('fos_user.user_manager');
    			$user = new User();
    			$user = $userManager->findUserBy(array('username' => $username));
    			
    			if (strcmp($this->getUser()->getUsername(), $user->getUsername()) == 0 ) {
    			
    			
	    			$newSubscription = new Friday_Subscriptions();
	    			$newSubscription->setDate($dayOfSubscription);
	    			if ($roleSubscription == 1) {
	    				$newSubscription->setUser($user);
	    			} else {
	    				$newSubscription->setBackupUser($user);
	    			}
	    			
	    			$em->persist($newSubscription);
	    			$em->flush();
	    			
	    			$output = array ('success' => TRUE,
	    							'message' => 'Subscription complete!',
	    							'user' => $user->getUsername()
	    			);
    			}
    			else {
    				$output = array ('success' => FALSE,
    						'message' => 'The subscriber must be the same as the user logged in!'
    				);
    			}
    			
    			
    		} elseif (
    				($roleSubscription == 1 and 
    					is_null($friday_subscriber->getUser())) or 
    				($roleSubscription == 0 and
    					is_null($friday_subscriber->getBackupUser()))
    					
    				) {
    			
    			$userManager = $this->get('fos_user.user_manager');
    			$user = new User();
    			$user = $userManager->findUserBy(array('username' => $username));
    			
    			if (strcmp($this->getUser()->getUsername(), $user->getUsername()) == 0 ) {
    				
    				if ($roleSubscription == 1) {
    					$friday_subscriber->setUser($user);
    				} else {
    					$friday_subscriber->setBackupUser($user);
    				}
    			
    				$em->persist($friday_subscriber);
    				$em->flush();
    			
    				$output = array ('success' => TRUE,
    						'message' => 'Subscription complete!',
    						'user' => $user->getUsername()
    				);
    			}
    			else {
    				$output = array ('success' => FALSE,
    						'message' => 'The subscriber must be the same as the user logged in!'
    				);
    			}
    			
    			
    		} else {
    			$output = array ('success' => FALSE,
    					'message' => 'A user is already subscribed!'
    			);
    		}
    		
    		$response->headers->set('Content-Type', 'application/json');
    		$response->setContent(json_encode($output));
    		return $response;
    		
    	}
    	
    	$output = array ('success' => FALSE,
    			'message' => 'isXmlHttpRequest FAILED!'
    	);
    	
    	$response = new Response();
    	$response->headers->set('Content-Type', 'application/json');
    	$response->setContent(json_encode($output));
    	return $response;
    	
    }
    
    
    public function unsubscribeCroissantAction()
    {
    	$request = $this->getRequest();
    	 
    	if ($request->isXmlHttpRequest()) {
    	
    		$response = new Response();
    		
    		$dayOfSubscription = DateTime::createFromFormat("dmY", $request->request->get('date'));
    		$dayOfSubscription->setTime(0,0);
    		
    		$roleSubscription = $request->request->get('role');
    		
    		$username = $request->request->get('username');
    		
    		$em = $this->getDoctrine()->getManager();
    		
    		$repositoryFS = $em->getRepository('STXCroissantsBundle:Friday_Subscriptions');
    		$friday_subscriber = $repositoryFS->findOneBy(array('date' => $dayOfSubscription));
    		
    		if (!is_null($friday_subscriber)){
    			$userManager = $this->get('fos_user.user_manager');
    			$user = new User();
    			$user = $userManager->findUserBy(array('username' => $username));
    			 
    			if (strcmp($this->getUser()->getUsername(), $user->getUsername()) == 0 ) {
    				
    				if ($roleSubscription == 1) {
    					$friday_subscriber->removeUser();
    				} else {
    					$friday_subscriber->removeBackupUser();
    				}
    				
    				$em->persist($friday_subscriber);
    				$em->flush();
    				 
    				$output = array ('success' => TRUE,
    						'message' => 'Unsubscription complete!',
    						'user' => $user->getUsername()
    				);
    				
    			} else {
    				$output = array ('success' => FALSE,
    						'message' => 'The subscriber must be the same as the user logged in!'
    				);
    			}
    		} else {
    			
    			$output = array ('success' => FALSE,
    							'message' => 'The date has no susbcriber!'
    			);
    			
    		}
    		
    		$response->headers->set('Content-Type', 'application/json');
    		$response->setContent(json_encode($output));
    		return $response;
    	}
    	
    	
    }
    
    public function confirmUserAction() {
    	
    	$request = $this->getRequest();
    	
    	if ($request->isXmlHttpRequest()) {
    		
    		$response = new Response();
    		
    		try {
    		
	    		$dayOfConfirmation = DateTime::createFromFormat("dmY", $request->request->get('date'));
	    		$dayOfConfirmation->setTime(0,0);
	    		
	    		$username = $request->request->get('username');
	    		
	    		$em = $this->getDoctrine()->getManager();
	    		
	    		$repositoryFS = $em->getRepository('STXCroissantsBundle:Friday_Subscriptions');
	    		$friday_subscribtion = $repositoryFS->findOneBy(array('date' => $dayOfConfirmation));
	    		
	    		if (is_null($friday_subscribtion)){
	    			$friday_subscribtion = new Friday_Subscriptions();
	    			$friday_subscribtion->setDate($dayOfConfirmation);
	    		}
	    		
	    		$userManager = $this->get('fos_user.user_manager');
	    		$confirmationUser = new User();
	    		$confirmationUser = $userManager->findUserBy(array('username' => $username));
	    		
	    		$friday_subscribtion->setConfirmationUser($confirmationUser);
	    		
	    		$em->persist($friday_subscribtion);
	    		$em->flush();
	    		
	    		//$this->get('session')->getFlashBag()->add('success', 'Utilisateur ' . $username . 'a apporté les croissants le ' . $dayOfConfirmation .'!');
	    		
	    		$output = array ('success' => TRUE,
	    				'message' => 'Confirmation complete!',
	    				'user' => $confirmationUser->getUsername()
	    		);
    		} catch (\Exception $e) {
    				
    				//$this->get('session')->getFlashBag()->add('error', 'Erreur survenue!');
    				$output = array ('success' => FALSE,
    						'message' => 'An undefined exception occurred!'
    				);
    		}
    		
    		
    		$response->headers->set('Content-Type', 'application/json');
    		$response->setContent(json_encode($output));
    		return $response;
    	}
    	
    }
    
    public function updateRemarkAction() {
    	
    	$request = $this->getRequest();
    	 
    	if ($request->isXmlHttpRequest()) {
    		
    		$response = new Response();
    		
    		try {
    		
    			$dayOfSubscription = DateTime::createFromFormat("dmY", $request->request->get('date'));
    			$dayOfSubscription->setTime(0,0);
    			
    			$em = $this->getDoctrine()->getManager();
    			 
    			$repositoryFS = $em->getRepository('STXCroissantsBundle:Friday_Subscriptions');
    			$friday_subscribtion = $repositoryFS->findOneBy(array('date' => $dayOfSubscription));
    			
    			if (is_null($friday_subscribtion)){
    				$friday_subscribtion = new Friday_Subscriptions();
    				$friday_subscribtion->setDate($dayOfSubscription);
    			}
    			
    			$friday_subscribtion->setRemark($request->request->get('remark'));
    			 
    			$em->persist($friday_subscribtion);
    			$em->flush();
    			
    			
    			$output = array ('success' => TRUE,
    					'message' => 'Confirmation complete!'
    			);
    		} catch (\Exception $e) {
    		
    			//$this->get('session')->getFlashBag()->add('error', 'Erreur survenue!');
    			$output = array ('success' => FALSE,
    					'message' => 'An undefined exception occurred!'
    			);
    		}
    		
    		
    		$response->headers->set('Content-Type', 'application/json');
    		$response->setContent(json_encode($output));
    		return $response;
    		
    	}
    }
    
    
    public function statsAction()
    {
    	
    	$statsOverYear = $this->getDoctrine()
    						->getRepository('STXCroissantsBundle:Friday_Subscriptions')
    						->getStatsForFridaySubscriptionsOverYear();
    	
    	return $this->render('STXCroissantsBundle:Croissants:stats.html.twig', array('stats' => $statsOverYear));
    }
    
    public function prefsAction()
    {
    	return $this->render('STXCroissantsBundle:Croissants:prefs.html.twig');
    }
    
    public function listAction()
    {
    	return $this->render('STXCroissantsBundle:Croissants:list.html.twig');
    }
    
}
