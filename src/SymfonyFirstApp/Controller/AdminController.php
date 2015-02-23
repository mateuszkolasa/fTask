<?php
namespace SymfonyFirstApp\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SymfonyFirstApp\Entity\Task;
use SymfonyFirstApp\Form\TaskType;
use SymfonyFirstApp\Entity\Category;
use SymfonyFirstApp\Form\CategoryTypeType;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class AdminController extends Controller {
    /**
     * @Route("/admin", name="SymfonyFirstApp_admin")
     */
    public function indexAction() {
    	$sm = $this->get('session_message');
    	$sm->test();
    	
    	
    	return $this->render('SymfonyFirstApp:Admin:index.html.twig',
    		array('users' => $this->getUsersList())
    	);
    }
    
    private function getUsersList() {
    	$em = $this->getDoctrine()->getEntityManager();
    	return $em->getRepository('SymfonyFirstApp:User')->findAllWithTasks();
    }
}