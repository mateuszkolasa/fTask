<?php
namespace SymfonyFirstApp\Notifications;

use SymfonyFirstApp\Interfaces\NotifyInterface;
use SymfonyFirstApp\Entity\User;

class ListNotification implements NotifyInterface {
	
	protected $user;
	
	public function __construct(User $user) {
		$this->user = $user;
	}
	
	public function getTitle() {
		return 'Lista Twoich zadań';
	}
	
	public function getMessage() {
		$tasks = $this->user->getActiveTasks();
		return array(
			'user' => $this->user->getUsername(),
			'tasks' => $tasks
		);
	}
	
	public function getTemplate() {
		return 'SymfonyFirstApp:Mails:listTask.html.twig';
	}
	
}