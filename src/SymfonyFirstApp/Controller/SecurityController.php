<?php
namespace SymfonyFirstApp\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

use SymfonyFirstApp\Entity\User;
use SymfonyFirstApp\Form\UserType;

class SecurityController extends Controller {
	/**
     * @Route("/login", name="SymfonyFirstApp_login")
     */
    public function loginAction(Request $request) {
    	$form = $this->createForm(new UserType(), new User());
    	
    	$authenticationUtils = $this->get('security.authentication_utils');
    	$error = $authenticationUtils->getLastAuthenticationError();
    	
    	if($error !== null) {
    		$this->get('session_message')->setWarning('Podane hasła różnią się od siebie');
    	}
    	
    	$lastUsername = $authenticationUtils->getLastUsername();
    	 
    	return $this->render('SymfonyFirstApp:Security:form.html.twig', array('form' => $form->createView()));
    }
    
	/**
     * @Route("/login_check", name="SymfonyFirstApp_loginCheck")
     */
    public function loginCheckAction(Request $request) {}
    
	/**
     * @Route("/register", name="SymfonyFirstApp_register")
     */
    public function registerAction(Request $request) {
    	$form = $this->createForm(new UserType(), new User());
    	return $this->render('SymfonyFirstApp:Security:register.html.twig', array('form' => $form->createView()));
    }
    
	/**
     * @Route("/register_action", name="SymfonyFirstApp_registerAction")
     */
    public function registerDoAction(Request $request) {
    	$user = new User();
    	$form = $this->createForm(new UserType(), $user);
    	$form->handleRequest($request);
    	    	
    	if($form->isValid()) {
    		$this->get('session_message')->setSuccess('Możesz się zalogować');
    		
    		$encoder = $this->get('security.password_encoder');
    		$user->setPassword($encoder->encodePassword($user, $user->getPassword()));
    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($user);
    		$em->flush();
    		
	    	return $this->render('SymfonyFirstApp:Security:form.html.twig');
    	}
    	
    	$this->get('session_message')->setWarning('Podane hasła różnią się od siebie');
    	return $this->render('SymfonyFirstApp:Security:register.html.twig', array('form' => $form->createView()));
     }
    
    /**
     * @Route("/logout", name="SymfonyFirstApp_logout")
     */
    public function logoutAction() {
    	$this->get('request')->getSession()->invalidate();
    	$this->get('session_message')->setSuccess('Zostałeś pomyślnie wylogowany.');
    	return $this->redirect($this->generateUrl('SymfonyFirstApp_login'));
    }
}