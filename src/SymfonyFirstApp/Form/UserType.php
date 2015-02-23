<?php
namespace SymfonyFirstApp\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
	    	->add('username', 'text')
	    	->add('password', 'repeated', array(
	    		'type' => 'password',
    			'required' => true
	    	))
	    	->add('save', 'submit');
	}
	
	public function getName() {
		return 'user';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'SymfonyFirstApp\Entity\User',
		));
	}
}