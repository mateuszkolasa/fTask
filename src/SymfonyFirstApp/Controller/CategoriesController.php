<?php
namespace SymfonyFirstApp\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SymfonyFirstApp\Entity\Category;
use SymfonyFirstApp\Form\CategoryType;

class CategoriesController extends Controller {    
    /**
     * @Route("/categories", name="SymfonyFirstApp_categories")
     */
    public function indexAction() {
    	$form = $this->createForm(new CategoryType(), new Category());
    	
    	return $this->render(
    			'SymfonyFirstApp:Categories:categories.html.twig',
    			array(
    				'categories' => $this->getCategories(),
    				'form' => $form->createView()
    			)
    		);
    }
    

    /**
     * @Route("/categories/action_add", name="SymfonyFirstApp_categoriesActionAdd")
     * 
     */
    public function newCategoryAction(Request $request) {
    	$category = new Category();
    	
    	$category->setUser($this->getUser());
    	
    	$form = $this->createForm(new CategoryType(), $category);
    	$form->handleRequest($request);
    	 
    	if ($form->isValid()) {
    		$this->get('session_message')->setSuccess('Utworzono nową kategorię');
    	
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($category);
    		$em->flush();
    		
    		return $this->redirect($this->generateUrl('SymfonyFirstApp_categories'));
    	}
    	
    	$this->get('session_message')->setWarning('Podane dane są nieprawidłowe');
    	
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_categories'));
    }
    

    /**
     * @Route("/categories/edit/", name="SymfonyFirstApp_categoriesEdit")
     * @Method({"POST"})
     */
    public function editPageAction(Request $request) {
    	$post = $request->request->get('id');
    	$category = $this->getDoctrine()->getRepository('SymfonyFirstApp:Category')->find($post);
    	
    	$form = $this->createForm(new CategoryType(), $category);
    	    	
    	return $this->render('SymfonyFirstApp:Categories:edit.html.twig', array(
    			'form' => $form->createView(),
    			'category' => $category
    	));
    }
    

    /**
     * @Route("/categories/action_edit/{id}", name="SymfonyFirstApp_categoriesActionEdit")
     * @Method({"POST"})
     */
    public function editCategoryAction(Category $category, Request $request) {
    	$form = $this->createForm(new CategoryType(), $category);
    	$form->handleRequest($request);
    	
    	if($form->isValid()) {
    		$this->get('session_message')->setSuccess('Zapisano zmiany');
    	
    		$this->getDoctrine()->getManager()->flush();
    		return $this->redirect($this->generateUrl('SymfonyFirstApp_categories'));
    	}
    	
    	$this->get('session_message')->setDanger('Nie udało się zapisać zmian');
    	
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_categoriesEdit'));
    }
    

    /**
     * @Route("/categories/action_delete/", name="SymfonyFirstApp_categoriesDelete")
     * @Method({"POST"})
     */
    public function deleteCategoryAction(Request $request) {
    	$post = $request->request->get('id');
    	$category = $this->getDoctrine()->getRepository('SymfonyFirstApp:Category')->find($post);

    	foreach($category->getTasks() as $task) {
    		$task->setCategory();
    	}
    	
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($category);
    	$em->flush();
    	
    	$this->get('session_message')->setSuccess('Kategoria została usunięta');
    	    	
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_categories'));
    }
    
    private function getCategories() {
    	$em = $this->getDoctrine()->getManager();
    	return $em->getRepository('SymfonyFirstApp:Category')->findBy(array('user' => $this->getUser()));
    }
}
