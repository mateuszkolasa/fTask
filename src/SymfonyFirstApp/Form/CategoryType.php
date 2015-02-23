<?php
namespace SymfonyFirstApp\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
	    	->add('name', 'text')
	    	->add('color', 'choice', array(
	    		'choices'   => array('1' => 'Domyślny', '2' => 'Niebieski', '3' => 'Zielony', '4' => 'Błękitny', '5' => 'Żółty', '6' => 'Czerwony'),
	    		'required'  => true)
	    	)
	    	->add('save', 'submit');
	}
	
	public function getName() {
		return 'category';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'SymfonyFirstApp\Entity\Category',
		));
	}
}