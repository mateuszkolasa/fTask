<?php
namespace SymfonyFirstApp\Services;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class TemplatedMailSender {

	protected $mailer;
	protected $templating;
	private $address;
	private $title;
	private $message;
	
	public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating) {
		$this->mailer = $mailer;
		$this->templating = $templating;

		$this->address = 'noreply@domain.com';
		$this->title = 'This is default title';
		$this->message = 'This is default message.';
	}
	
	public function setAddress($address) {
		$this->address = $address;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}
	
	public function send($template) {
		$message = $this->mailer->createMessage()
		->setSubject($this->title)
		->setFrom('mania.kaczka@gmail.com')
		->setTo($this->address)
		->setBody(
			$this->templating->render(
				$template,
				$this->message
			),
			'text/html'
		);
		
		if (!$this->mailer->send($message, $failures)) {
			echo "Failures:";
			print_r($failures);
		}
	}

}