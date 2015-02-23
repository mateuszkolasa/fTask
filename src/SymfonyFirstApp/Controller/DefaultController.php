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

class DefaultController extends Controller {
    /**
     * @Route("/", name="SymfonyFirstApp_homepage")
     */
    public function indexAction() {
    	return $this->render('SymfonyFirstApp:Welcome:homepage.html.twig', array(
    			'tasks' => $this->getTasksList()
    	));
    }
    
    /**
     * @Route("/new", name="SymfonyFirstApp_new")
     */
    public function newAction() {
    	$task = new Task();
    	$task->setUserId($this->getUser());
    	
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
    	$task->setUserId($this->getUser());
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
    	$task->setUserId($this->getUser());
    	
    	$form = $this->createForm(new TaskType(), $task);
    	$form->handleRequest($request);
    	
    	if ($form->isValid()) {
    		$request->getSession()->getFlashBag()->add(
				'success',
				'Utworzono nowe zadanie'
    		);
    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($task);
    		$em->flush();
    		
    		return $this->redirect($this->generateUrl('SymfonyFirstApp_task', array('id' => $task->getId())));
    	}
    	
    	$request->getSession()->getFlashBag()->add(
    			'warning',
    			'Podane dane są nieprawidłowe'
    	);
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_new'));
    }
    
    /**
     * @Route("/action_update/{id}", name="SymfonyFirstApp_update")
     * @Method({"POST"})
     */
    public function updateTaskAction(Task $task, Request $request) {
    	$task->setUserId($this->getUser());
    	$form = $this->createForm(new TaskType(), $task);
    	$requestPost = $request->request->all();
    	$form->handleRequest($request);
    	
    	$this->getDoctrine()->getManager()->flush();
    	
    	$request->getSession()->getFlashBag()->add(
    			'success',
    			'Zadanie zostało zedytowane'
    	);
    	
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
    	
    	$request->getSession()->getFlashBag()->add(
    			'success',
    			'Zadanie zostało usunięte'
    	);
    	
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_homepage'));
    }

    /**
     * @Route("/action_done/{id}", name="SymfonyFirstApp_done")
     * @Method({"POST"})
     */
    public function setDoneTaskAction(Task $task) {
    	$em = $this->getDoctrine()->getManager();
    	$task->setIsEnded("1");
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_homepage'));
    }
    
    private function getTasksList() {
    	$user = $this->get('security.context')->getToken()->getUser();
    	$em = $this->getDoctrine()->getEntityManager();
    	return $em->getRepository('SymfonyFirstApp:Task')->findBy(array('userId' => $user->getId()));
    	
    	//return $this->getDoctrine()->getRepository('SymfonyFirstApp:Task')->findAll();
    }
}