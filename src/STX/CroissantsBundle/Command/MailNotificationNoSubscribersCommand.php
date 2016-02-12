<?php

namespace STX\CroissantsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;


class MailNotificationNoSubscribersCommand extends ContainerAwareCommand
{
	
	/* 
	 * Configuration of the command
	 */
	protected function configure() {
		$this
			->setName('notifyMailNoSubscribers:send')
			->setDescription('Notification when no subscribers')
			->addArgument('test', InputArgument::OPTIONAL, 'Do you want to test?')
		;
	}

	 
	/* 
	 * Here we peform the logic to notify the user which is subscriber to a friday
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		
		$fridayHoliday = $this->getContainer()
							->get('doctrine')
							->getRepository('STXCroissantsBundle:Friday_Subscriptions')
							->isNextFridayHoliday();
		
		/* if next friday is a holiday, then no notification must be sent */
		if ($fridayHoliday[0]['subscribers'] == 0) {
		
			$nbrOfSubscribers = $this->getContainer()
								->get('doctrine')
								->getRepository('STXCroissantsBundle:Friday_Subscriptions')
								->getNextFridaySubscribers();
			
			if (sizeof($nbrOfSubscribers) == 0) {
				
				$userList =  $this->getContainer()
									->get('doctrine')
									->getRepository('STXCroissantsBundle:Friday_Subscriptions')
									->getUsersListForEmail();
					
				$userArray = array();
					
				for ($i=0; $i < sizeof($userList); $i++) {
					$username = $userList[$i]['cu_lastname'] . ' ' . $userList[$i]['cu_firstname'];
					$userToAdd = array( $userList[$i]['cu_email'] => $username);
					array_push($userArray, $userToAdd);
				}
				
				
				$text = "Hello,<br>"
						."<br>"
						."C'est la catastrophe, il n'y a toujours personne inscrite pour ce vendredi!"
						."<br>"
						."Qui est le valereux volontaire qui sauvera la ter...errrr... notre sacro-saint vendredi ?"
						."<br>";
				
				$message = \Swift_Message::newInstance()
							->setSubject('[Croissants] ***** Personne pour vendredi! *****')
							->setFrom('noreply@croissants.stx.com')
							->setTo($userArray)
							->setBody($this->getContainer()->get('templating')->render('STXCroissantsBundle:CroissantsAdmin:notificationGeneral.txt.twig', array('emailbody' => $text) ));
				
				$transport = \Swift_MailTransport::newInstance();
				$mailer = \Swift_Mailer::newInstance($transport);
				
				$testing = $input->getArgument('test');
				$text = '';
				
				if ($testing) {
					$text = 'TESTING!!! ';
				} else {
					$mailer->send($message);
				}
				
				$output->writeln($text . "Notification sent to reming to subscribe for next friday!");
			} else {
				$output->writeln("No message sent");
			}
		}
	}

	
}