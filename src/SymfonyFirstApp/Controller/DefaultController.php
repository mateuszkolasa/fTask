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
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller {
    /**
     * @Route("/", name="SymfonyFirstApp_homepage")
     */
    public function indexAction() {
    	return $this->render('SymfonyFirstApp:Welcome:grid.html.twig');
    }
    
    /**
     * @Route("/list/{type}", name="SymfonyFirstApp_list")
     */
    public function listAction($type) {
    	return $this->render('SymfonyFirstApp:Welcome:homepage.html.twig', array(
    			'tasks' => $this->getTasksList($type)
    	));
    }
    
    /**
     * @Route("/new", name="SymfonyFirstApp_new")
     */
    public function newAction() {
    	$task = new Task();
    	$task->setUser($this->getUser());
    	
    	$form = $this->createForm(new TaskType(), $task);
    	
    	return $this->render(
    			'SymfonyFirstApp:Welcome:task.html.twig',
    			array(
    				'page' => 'add',
    				'form' => $form->createView()
    			)
    		);
    }
    
    /**
     * @Route("/task/{id}", name="SymfonyFirstApp_task")
     */
    public function taskAction(Task $task) {
    	$task->setUser($this->getUser());
    	$form = $this->createForm(new TaskType(), $task);
    	
    	return $this->render(
    			'SymfonyFirstApp:Welcome:taskEdit.html.twig',
    			array('page' => 'edit', 'form' => $form->createView(), 'task' => $task)
    		);
    }
    
    /**
     * @Route("/taskCategories/{id}", name="SymfonyFirstApp_taskCategories")
     */
    public function taskCategoriesAction(Category $category) {
    	return $this->render('SymfonyFirstApp:Welcome:homepage.html.twig', array('tasks' => $category->getTasks()));
    }
    
    /**
     * @Route("/action_add/", name="SymfonyFirstApp_add")
     * @Method({"POST"})
     */
    public function newTaskAction(Request $request) {
    	$task = new Task();
    	$task->setUser($this->getUser());
    	
    	$form = $this->createForm(new TaskType(), $task);
    	$form->handleRequest($request);
    	
    	if ($form->isValid()) {
    		$this->get('session_message')->setSuccess('Utworzono nowe zadanie');
    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($task);
    		$em->flush();
    		
    		return $this->redirect($this->generateUrl('SymfonyFirstApp_task', array('id' => $task->getId())));
    	}
    	
    	$this->get('session_message')->setWarning('Podane dane sa nieprawidłowe');
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_new'));
    }
    
    /**
     * @Route("/action_update/{id}", name="SymfonyFirstApp_update")
     * @Method({"POST"})
     */
    public function updateTaskAction(Task $task, Request $request) {
    	$task->setUser($this->getUser());
    	$form = $this->createForm(new TaskType(), $task);
    	$requestPost = $request->request->all();
    	$form->handleRequest($request);
    	
    	$this->getDoctrine()->getManager()->flush();
    	
    	$this->get('session_message')->setSuccess('Zadanie zostało zedytowane');
    	
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_task', array('id' => $task->getId())));
    }

    /**
     * @Route("/action_delete/{id}", name="SymfonyFirstApp_delete")
     * @Method({"POST"})
     */
    public function deleteTaskAction(Task $task) {
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($task);
    	$em->flush();
    	
    	$this->get('session_message')->setSuccess('Zadanie zostało usunięte');
    	
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_homepage'));
    }

    /**
     * @Route("/action_status/{id}", name="SymfonyFirstApp_status")
     *
     */
    public function changeStatusAction(Task $task) {
    	$em = $this->getDoctrine()->getManager();
    	$task->changeStatus();
    	$em->flush();

    	//$this->get('session_message')->setSuccess('Zadanie zostało oznaczone jako zrobione');
    	
    	return new JsonResponse(array('status' => $task->getStatus(), 'priority' => $task->getPriorityName()));
    }
    
    private function getTasksList($type = null) {
    	$em = $this->getDoctrine()->getManager();
    	
    	if($type == 'nojs') {
    	    return $em->getRepository('SymfonyFirstApp:Task')->findBy(array('user' => $this->getUser()));
    	}
    	
    	if($type !== null) {
    		$priority = array('low' => 1, 'normal' => 2, 'high' => 3);
    		return $em->getRepository('SymfonyFirstApp:Task')->findBy(array('user' => $this->getUser(), 'priority' => $priority[$type]));
    	}
    	
    	return $em->getRepository('SymfonyFirstApp:Task')->findArrayBy(array('user' => $this->getUser()));
    }
}