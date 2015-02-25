<?php
namespace SymfonyFirstApp\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SymfonyFirstApp\Notifications\ListNotification;

use Doctrine\Bundle\DoctrineBundle\Registry;

class TaskListCommand extends ContainerAwareCommand {
	
	protected $container;
	
	protected function configure() {
		$this->setName('sfa:tasklist')
		->setDescription('To to nie wiem co jest');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$this->container = $this->getContainer();
		$mailer = $this->container->get('user_notifications');
		
		$em = $this->container->get('doctrine')->getManager();
		$users = $em->getRepository('SymfonyFirstApp:User')->findAll();
		
		foreach($users as $user) {
			$output->writeln($user->getUsername());
			$mailer->send(new ListNotification($user), $user->getEmail());
		}
	}
}