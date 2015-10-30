<?php

namespace STX\CroissantsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

	public function alertAction()
	{
		return $this->render('STXCroissantsBundle:CroissantsAdmin:alert.html.twig');
	}

	public function mailAction(Request $request)
	{
		$defaultData = array('message' => 'Saisissez le message ici');
		$form = $this->createFormBuilder($defaultData)
			->add('email', 'email')
			->add('message', 'textarea')
			->add('send', 'submit')
			->getForm();
			
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			
			$data = $form->getData();
			
			$message = \Swift_Message::newInstance()
				->setSubject('Test email croissants')
				->setFrom('noreply@croissants.stx.com')
				->setTo($data['email'])
				->setBody($this->renderView('STXCroissantsBundle:CroissantsAdmin:test_email.txt.twig', array('emailbody' => $data['message']) ));
			
			$transport = \Swift_MailTransport::newInstance();
			$mailer = \Swift_Mailer::newInstance($transport);
			$mailer->send($message);
			
			//$this->get('mailer')->send($message);
			$this->get('session')->getFlashBag()->add('success', 'Email envoyé!');
			//return $this->redirect($this->generateUrl('stx_croissants_mail'));
			return $this->render('STXCroissantsBundle:CroissantsAdmin:mail.html.twig', array('form' => $form->createView()));
		}
		
		return $this->render('STXCroissantsBundle:CroissantsAdmin:mail.html.twig', array('form' => $form->createView()));
	}

}
