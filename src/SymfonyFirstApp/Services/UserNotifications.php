<?php
namespace SymfonyFirstApp\Services;

use SymfonyFirstApp\Services\TemplatedMailSender;
use SymfonyFirstApp\Interfaces\NotifyInterface;

class UserNotifications {
	
	protected $templatedMailSender;
	
	public function __construct(TemplatedMailSender $mailSender) {
		$this->templatedMailSender = $mailSender;
	}
	
	public function send(NotifyInterface $notifyToSend, $emailTo) {
		$this->templatedMailSender->setAddress($emailTo);
		$this->templatedMailSender->setTitle($notifyToSend->getTitle());
		$this->templatedMailSender->setMessage($notifyToSend->getMessage());
		$this->templatedMailSender->send($notifyToSend->getTemplate());
	}

}