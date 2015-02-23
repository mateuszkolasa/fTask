<?php
namespace SymfonyFirstApp\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class TaskType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$task = $builder->getData();

		$builder
	    	->add('title', 'text')
	    	->add('priority', 'choice', array(
	    		'choices'   => array('1' => 'Niski', '2' => 'Normalny', '3' => 'Wysoki'),
	    		'required'  => true)
	    	)
	    	->add('date', 'date')
	    	->add('status', 'choice', array(
	    		'choices'   => array('1' => 'Otwarte', '0' => 'Zamkniete'),
	    		'required'  => true)
	    	)
	    	->add('category', 'entity', array(
				'class'=>'SymfonyFirstApp\Entity\Category',
				'property' => 'name',
				'empty_value' => '--- brak ---',
				'required' => false,
    			'query_builder' => function(EntityRepository $er) use ($task) {
    				return $er->createQueryBuilder('categories')
    				->where('categories.user = '.$task->getUser()->getId());
    			}
	    	))
	    	->add('save', 'submit');
	}

	public function getName() {
		return 'task';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'SymfonyFirstApp\Entity\Task',
		));
	}
}