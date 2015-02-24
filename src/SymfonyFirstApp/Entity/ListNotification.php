<?php
namespace SymfonyFirstApp\Entity;

use SymfonyFirstApp\Interfaces\NotifyInterface;

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