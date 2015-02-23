<?php
namespace SymfonyFirstApp;

use Symfony\Component\HttpFoundation\Session\Session;

class SessionMessage {
	
	protected $session;
	
	public function __construct(Session $session) {
		$this->session = $session;
	}
	
	public function setSuccess($message) {
		$this->session->getFlashBag()->add(
			'success',
			$message
		);
	}
	
	public function setWarning($message) {
		$this->session->getFlashBag()->add(
			'warning',
			$message
		);
	}
	
	public function setDanger($message) {
		$this->session->getFlashBag()->add(
			'danger',
			$message
		);
	}
	
}