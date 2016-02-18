<?php

namespace STX\CroissantsBundle\Controller;

use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use STX\CroissantsBundle\Entity\Friday_Subscriptions;
use Symfony\Component\HttpFoundation\Response;
use STX\CroissantsBundle\Form\FridaySubscriptionType;

class CroissantsAdminController extends Controller
{
	public function adminAction()
	{
		return $this->render('STXCroissantsBundle:CroissantsAdmin:admin.html.twig');
	}
	
	public function importusersAction()
	{
		$em = $this->getDoctrine()->getManager();
	}

	public function holidaysAction(Request $request)
	{
		if ($request->isMethod('POST'))
		{
			$eventName = $request->request->get('event');
			$eventDate = $request->request->get('date');
			
			if (!is_null($eventDate) && !empty($eventName) ) {
				$dateForHoliday=DateTime::createFromFormat("d/m/Y", $eventDate);
				$dateForHoliday->setTime(0, 0);
				
				
				$em = $this->getDoctrine()->getManager();
				$repositoryFS = $em->getRepository('STXCroissantsBundle:Friday_Subscriptions');
				$subscription = $repositoryFS->findOneBy(array('date' => $dateForHoliday));
				
				if (!is_null($subscription)) {
					/* THROW ERROR and return  form */
					
					$this->get('session')->getFlashBag()->add('danger', 'Il y a déjà un évènement ce jour!');
					//return $this->render('STXCroissantsBundle:CroissantsAdmin:holidays.html.twig');
					
				} else {
					
					/* Create new friday_subscription for holiday */
					
					$holidayToAdd = new Friday_Subscriptions();
					$holidayToAdd->setDate($dateForHoliday);
					$holidayToAdd->setRemark('FERIE - ' . $eventName);
					
					$em->persist($holidayToAdd);
					$em->flush();
					
					$this->get('session')->getFlashBag()->add('success', 'Jour férié ajouté au calendrier');
					//return $this->render('STXCroissantsBundle:CroissantsAdmin:holidays.html.twig');
				}
				
			} else {
				
				/* THROW ERROR and return  form */
				$this->get('session')->getFlashBag()->add('danger', 'Un des champs est vide!');
				//return $this->render('STXCroissantsBundle:CroissantsAdmin:holidays.html.twig');
				
			}
			
			
		}
			
		return $this->render('STXCroissantsBundle:CroissantsAdmin:holidays.html.twig' );
	}
	
	
	public function holidaylistAction() {
		
		$holidayList =  $this->getDoctrine()
							->getRepository('STXCroissantsBundle:Friday_Subscriptions')
							->getHolidays();
		
		$holidayArray = array();
			
		for ($i=0; $i < sizeof($holidayList); $i++) {
			$holidayToAdd = array( 'date' => $holidayList[$i]->getDate(), 'remark' => $holidayList[$i]->getRemark() );
			array_push($holidayArray, $holidayToAdd);
		}
		
		$template = $this->render('STXCroissantsBundle:CroissantsAdmin:holidaylist.html.twig', array('holidays' => $holidayArray));
		
		return $template;
	}
	
	
	/**
	 * Ajax function for deleting an holiday
	 */
	public function deleteHolidayAction()
	{
		$request = $this->getRequest();
			
		if ($request->isXmlHttpRequest()) {
			
			//$response = new Response();
			
			$holidayDate = DateTime::createFromFormat("dmY", $request->request->get('date'));
			$holidayDate->setTime(0,0);
			
			$em = $this->getDoctrine()->getManager();
    		
    		$repositoryFS = $em->getRepository('STXCroissantsBundle:Friday_Subscriptions');
    		$holiday = $repositoryFS->findOneBy(array('date' => $holidayDate));
    		
    		if (!is_null($holiday)) {
    			
    			$em->remove($holiday);
    			$em->flush();
    			
    			$holidayList =  $this->getDoctrine()
    								->getRepository('STXCroissantsBundle:Friday_Subscriptions')
    								->getHolidays();
    			
    			$holidayArray = array();
    				
    			for ($i=0; $i < sizeof($holidayList); $i++) {
    				$holidayToAdd = array( 'date' => $holidayList[$i]->getDate(), 'remark' => $holidayList[$i]->getRemark() );
    				array_push($holidayArray, $holidayToAdd);
    			}
    			
    			
    			$template = $this->render('STXCroissantsBundle:CroissantsAdmin:holidaylist.html.twig', array('holidays' => $holidayArray));
    			
    			$output = array ('success' => TRUE,
    					'message' => 'Holiday removed!',
    					'template' => $template
    			);
    			
    		} else {
    			$output = array ('success' => FALSE,
    					'message' => 'No holiday found to remove!'
    			);
    		}
		} else {
			$output = array ('success' => FALSE, 'message' => 'isXmlHttpRequest FAILED!');
		}
			
		$response = new Response();
		$response->headers->set('Content-Type', 'application/json');
		$response->setContent(json_encode($output));
		return $response;
	}

	public function mailAction(Request $request)
	{
		$defaultData = array('sujet' => '[Croissants] ', 'message' => 'Saisissez le message ici');
		$form = $this->createFormBuilder($defaultData)
			->add('sujet', 'text')
			->add('message', 'textarea')
			->getForm();
			
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			
			$data = $form->getData();
			
			$userList =  $this->getDoctrine()
								->getRepository('STXCroissantsBundle:Friday_Subscriptions')
								->getUsersListForEmail();
			
			$userArray = array();
			
			/*
			for ($i=0; $i < sizeof($userList); $i++) {
				$username = $userList[$i]['cu_lastname'] . ' ' . $userList[$i]['cu_firstname'];
				$userToAdd = array( $userList[$i]['cu_email'] => $username);
				array_push($userArray, $userToAdd);
			}
			*/
			
			for ($i=0; $i < sizeof($userList); $i++) {
				$userArray[$userList[$i]['cu_email']] = $userList[$i]['cu_lastname'] . ' ' . $userList[$i]['cu_firstname'];
			}
			
								
			$message = \Swift_Message::newInstance()
				->setSubject($data['sujet'])
				->setFrom('noreply@croissants.stx.com')
				->setTo($userArray)
				->setBody($this->renderView('STXCroissantsBundle:CroissantsAdmin:notificationGeneral.html.twig', array('emailbody' => nl2br($data['message'])) ),
						'text/html');
			
			$transport = \Swift_MailTransport::newInstance();
			$mailer = \Swift_Mailer::newInstance($transport);
			
			try {
				$mailer->send($message);
				$this->get('session')->getFlashBag()->add('success', 'Email envoyé!');
			} catch (Exception $e)
			{
				$error_message = $e->getMessage();
				$this->get('session')->getFlashBag()->add('notice', $error_message);
			}
			
			
			return $this->render('STXCroissantsBundle:CroissantsAdmin:mail.html.twig', array('form' => $form->createView()));
		}
		
		return $this->render('STXCroissantsBundle:CroissantsAdmin:mail.html.twig', array('form' => $form->createView()));
	}
	
	
	public function testPageAction()
	{
				
		return $this->render('STXCroissantsBundle:CroissantsAdmin:test_page_admin.html.twig');
		
	}
	
	/**
	 * Ajax function for test1
	 */
	public function test1Action()
	{
		$request = $this->getRequest();
		 
		if ($request->isXmlHttpRequest()) {
	
			$response = new Response();
			
			$fridayHoliday = $this->getDoctrine()->getRepository('STXCroissantsBundle:Friday_Subscriptions')->isNextFridayHoliday();
			
			if ($fridayHoliday[0]['subscribers'] == 0) {
				
				$nbrOfSubscribers = $this->getDoctrine()
										->getRepository('STXCroissantsBundle:Friday_Subscriptions')
										->getNextFridaySubscribers();
					
				if ($nbrOfSubscribers[0]['subscribers'] == 0) {
				
					$userList =  $this->getDoctrine()
									->getRepository('STXCroissantsBundle:Friday_Subscriptions')
									->getUsersListForEmail();
						
					$userArray = array();
						
					for ($i=0; $i < sizeof($userList); $i++) {
						$username = $userList[$i]['cu_lastname'] . ' ' . $userList[$i]['cu_firstname'];
						$userToAdd = array( 'user' => $username, 'email' => $userList[$i]['cu_email'] );
						array_push($userArray, $userToAdd);
					}
				
					$output = array ('success' => TRUE,
						'message' => 'Pas de vacances et pas d\'inscrits !',
						'listofmails' => $userArray
					);
				} else {
					
					$output = array ('success' => TRUE,
							'message' => 'Pas de vacances ET une inscription!',
							'listofmails' => null
					);
					
				}
			} else {
				$output = array ('success' => TRUE,
						'message' => 'Il y a un jour de vacances!'
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
	
	/**
	 * Ajax function for test2
	 */
	public function test2Action()
	{
		$request = $this->getRequest();
			
		if ($request->isXmlHttpRequest()) {
	
			$response = new Response();
			
			$nextUserData = $this->getDoctrine()
								->getRepository('STXCroissantsBundle:Friday_Subscriptions')
								->getNextUserForFridaySubscriptions();
			
			if (sizeof($nextUserData) > 0) {
					
				$email = $nextUserData[0]->getUser()->getEmail();
				
				$output = array ('success' => TRUE,
						'message' => 'Rappel à envoyer à ' . $email
				);
				
			} else {
				
				$output = array ('success' => TRUE,
						'message' => 'Pas de rappel à envoyer à utilisateur principal'
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
	
	
	
	/**
	 * Ajax function for test3
	 */
	public function test3Action()
	{
		$request = $this->getRequest();
			
		if ($request->isXmlHttpRequest()) {
	
			$response = new Response();
			
			$nextUserData = $this->getDoctrine()
								->getRepository('STXCroissantsBundle:Friday_Subscriptions')
								->getNextBackupForFridaySubscriptions();
				
			if (sizeof($nextUserData) > 0) {
					
				$email = $nextUserData[0]->getUser()->getEmail();
			
				$output = array ('success' => TRUE,
						'message' => 'Rappel à envoyer à ' . $email
				);
			
			} else {
			
				$output = array ('success' => TRUE,
						'message' => 'Pas de rappel à envoyer au backup'
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
	
	
	/**
	 * Insert manually a subscription (in case of DB transfer or other problems)
	 * 
	 */
	public function insertsubscriptionAction(Request $request)
	{
		$newSubscription = new Friday_Subscriptions();
		
		$form = $this->createForm(new FridaySubscriptionType(), $newSubscription);
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($newSubscription);
			$em->flush();
		}
		
		return $this->render('STXCroissantsBundle:CroissantsAdmin:addfridaysubscription.html.twig', array('form' => $form->createView()));
		
	}
	
}
