<?php
namespace SymfonyFirstApp\Notifications;

use SymfonyFirstApp\Interfaces\NotifyInterface;
use SymfonyFirstApp\Entity\Task;

class TaskNotification implements NotifyInterface {
	
	protected $task;
	
	public function __construct(Task $task) {
		$this->task = $task;
	}
	
	public function getTitle() {
		return 'Nowe zadanie: ' . $this->task->getTitle();
	}
	
	public function getMessage() {
		return array(
			'user' => $this->task->getUser()->getUsername(),
			'title' => $this->task->getTitle()
		);
	}
	
	public function getTemplate() {
		return 'SymfonyFirstApp:Mails:newTask.html.twig';
	}
	
}