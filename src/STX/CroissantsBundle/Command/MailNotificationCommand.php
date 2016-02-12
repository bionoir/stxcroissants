<?php

namespace STX\CroissantsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;


class MailNotificationCommand extends ContainerAwareCommand
{
	
	/* 
	 * Configuration of the command
	 */
	protected function configure() {
		$this
			->setName('notifyMail:send')
			->setDescription('Notification for subscribers')
			->addArgument('test', InputArgument::OPTIONAL, 'Do you want to test?')
		;
	}

	 
	/* 
	 * Here we peform the logic to notify the user which is subscriber to a friday
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		
		$nextUserData = $this->getDoctrine()
							->getRepository('STXCroissantsBundle:Friday_Subscriptions')
							->getNextUserForFridaySubscriptions();
		
		if (sizeof($nextUserData) > 0) {
			
			$email = $nextUserData[0]->getUser()->getEmail();
		
		
			$text = "Salut,<br>"
					."<br>"
					."Tu es inscrit pour amener les croissants en <b>principal</b> pour ce vendredi";
		
			$message = \Swift_Message::newInstance()
						->setSubject('[Croissants]Rappel pour ce vendredi!')
						->setFrom('noreply@croissants.stx.com')
						->setTo($email)
						->setBody($this->getContainer()->get('templating')->render('STXCroissantsBundle:CroissantsAdmin:test_email.txt.twig', array('emailbody' => $text) ));
			
			$transport = \Swift_MailTransport::newInstance();
			$mailer = \Swift_Mailer::newInstance($transport);
			$testing = $input->getArgument('test');
			$text = '';
				
			if ($testing) {
				$text = 'TESTING!!! ';
			} else {
				$mailer->send($message);
			}
			
			$output->writeln($text . "Message sent to " . $email);
		} else {
			$output->writeln("No message sent");
		}

	}

	
}