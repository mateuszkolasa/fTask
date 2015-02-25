<?php
namespace SymfonyFirstApp\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use SymfonyFirstApp\Entity\Task;
use SymfonyFirstApp\Services\TemplatedMailSender;

use Symfony\Component\DependencyInjection\ContainerInterface;
use SymfonyFirstApp\Notifications\TaskNotification;

class AdminMailer {

	protected $container;
	protected $notificationSender;
	
	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}
	
	public function postPersist(LifecycleEventArgs $args) {
		$this->notificationSender = $this->container->get('user_notifications');
		$entity = $args->getEntity();
		
		if ($entity instanceof Task) {
			$this->notificationSender->send(new TaskNotification($entity), $this->container->getParameter('admin_mail'));
		}
	}
}