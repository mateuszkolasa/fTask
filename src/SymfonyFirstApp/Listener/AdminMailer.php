<?php
namespace SymfonyFirstApp\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use SymfonyFirstApp\Entity\Task;
use SymfonyFirstApp\Services\MailSender;
use SymfonyFirstApp\Listener\Mailer;

class AdminMailer {
	
	protected $mailer;
	
	public function __construct(MailSender $mailer) {
		$this->mailer = $mailer;
	}
	
	public function postPersist(LifecycleEventArgs $args) {
		$entity = $args->getEntity();
		
		if ($entity instanceof Task) {
			$this->mailer->setMessage('Test');
			$this->mailer->send();
			
			die('Siemaneczko!');
		}
		
		die('---');
	}
}