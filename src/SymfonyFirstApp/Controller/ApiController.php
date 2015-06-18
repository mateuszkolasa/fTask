<?php
namespace SymfonyFirstApp\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use SymfonyFirstApp\Entity\Category;
use SymfonyFirstApp\Form\CategoryType;
use SymfonyFirstApp\Entity\Task;
use SymfonyFirstApp\Form\TaskType;
use SymfonyFirstApp\Form\CategoryTypeType;

class ApiController extends Controller {

    /**
     * @Route("/api/tasks", name="SymfonyFirstApp_api_tasks")
     */
    public function apiTasksAction() {
        return new JsonResponse($this->getTasksList());
    }
       

    /**
     * @Route("/api/add/task", name="SymfonyFirstApp_api_add_task")
     * Method({"POST"})
     */
    public function apiAddTaskAction(Request $request) {
        if($params['title'] == null) {
            return new JsonResponse(array('error' => 'Title cannot be null'));
        }
        
        $task = new Task();
        $task->setTitle($params['title']);
        $task->setStatus($params['status']);
        
        if(array_key_exists('category', $params) && $params['category'] != null) {
            $params['category'] = $this->getDoctrine()->getManager()->getRepository('SymfonyFirstApp:Category')->findOneBy(array('name' => $params['category']));
            if($params['category'] != null) {
                $task->setCategory($params['category']);
            }
        }
        
        if($params['priority'] == 'info') $params['priority'] = 1;  
        if($params['priority'] == 'warning') $params['priority'] = 2;  
        if($params['priority'] == 'danger') $params['priority'] = 3;
        $task->setPriority($params['priority']);
        $task->setDate(new \DateTime());
        
        $task->setUser($this->getUser());
        
        $this->getDoctrine()->getManager()->persist($task);
        $this->getDoctrine()->getManager()->flush();
        
        return new JsonResponse(array('newTask' => $task->toArray()));
    }

    /**
     * @Route("/api/categories", name="SymfonyFirstApp_api_categories")
     */
    public function apiCategoriesAction() {
        return new JsonResponse($this->getCategoriesList());
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

    private function getCategoriesList($type = null) {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('SymfonyFirstApp:Category')->findArrayBy(array('user' => $this->getUser()));
    }
}