<?php

namespace STX\CroissantsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class MailNotificationCommand extends ContainerAwareCommand
{
	
	/* 
	 * Configuration of the command
	 */
	protected function configure() {
		$this
			->setName('notifyMail:send')
			->setDescription('Notification for subscribers')
			/*->addArgument()
			->addOption()*/
		;
	}

	 
	/* 
	 * Here we peform the logic to notify the user which is subscriber to a friday
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		
		$text = "Ceci est un test de notification automatique";
		
		$message = \Swift_Message::newInstance()
						->setSubject('Test email croissants')
						->setFrom('noreply@croissants.stx.com')
						->setTo('fabio.dalmasso@secutix.com')
						->setBody($this->getContainer()->get('templating')->render('STXCroissantsBundle:CroissantsAdmin:test_email.txt.twig', array('emailbody' => $text) ));
			
		$transport = \Swift_MailTransport::newInstance();
		$mailer = \Swift_Mailer::newInstance($transport);
		$mailer->send($message);
		
		$output->writeln("Message sent :" . $text);

	}

	
}