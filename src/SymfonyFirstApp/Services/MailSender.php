<?php
namespace SymfonyFirstApp\Services;

use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;

class MailSender {

	protected $mailer;
	protected $twig;
	private $message;
	
	public function __construct(\Swift_Mailer $mailer, TimedTwigEngine $twig) {
		$this->mailer = $mailer;
		$this->twig = $twig;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}
	
	public function send() {
		$message = $this->mailer->createMessage()
		->setSubject('You have Completed Registration!')
		->setFrom('mateusz.kolasa@polcode.net')
		->setTo('mateusz.kolasa@polcode.net')
		->setBody(
			/*$this->renderView(
			 // app/Resources/views/Emails/registration.html.twig
					'Emails/registration.html.twig',
					array('name' => $name)
			),*/
			$this->message,
			'text/html'
		);
		
		//$this->mailer->send($message);
		$f = fopen("C:/TEMP/test.html", "w+");
		fputs($f, $this->twig->renderView(
			 'SymfonyFirstApp:mail.html.twig', array('title' => 'Nagłówek', 'content' => $this->message)));
		fclose($f);
	}

}