<?php

namespace STX\CroissantsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;


class MailNotificationBackupCommand extends ContainerAwareCommand
{
	
	/* 
	 * Configuration of the command
	 */
	protected function configure() {
		$this
			->setName('notifyMailBackup:send')
			->setDescription('Notification for backup subscribers')
		;
	}

	 
	/* 
	 * Here we peform the logic to notify the user which is subscriber to a friday
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		
		$nextUserData = $this->getDoctrine()
							->getRepository('STXCroissantsBundle:Friday_Subscriptions')
							->getNextBackupForFridaySubscriptions();
		
		if (sizeof($nextUserData) > 0) {
			
			$email = $nextUserData[0]->getUser()->getEmail();
		
		
			$text = "Salut,<br>"
					."<br>"
					."Il n'y a pas d'ameneur principal et tu es inscrit pour amener les croissants en <b>backup</b> pour ce vendredi";
		
			$message = \Swift_Message::newInstance()
						->setSubject('[Croissants]Rappel pour ce vendredi!')
						->setFrom('noreply@croissants.stx.com')
						->setTo($email)
						->setBody($this->getContainer()->get('templating')->render('STXCroissantsBundle:CroissantsAdmin:test_email.txt.twig', array('emailbody' => $text) ));
			
			$transport = \Swift_MailTransport::newInstance();
			$mailer = \Swift_Mailer::newInstance($transport);
			$mailer->send($message);
			
			$output->writeln("Message sent to " . $email);
		} else {
			$output->writeln("No message sent");
		}

	}

	
}