<?php
namespace SymfonyFirstApp\Entity;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository {
	
   public function findArrayBy($criteria = array())
    {
        $tasks = $this->findBy($criteria);
        
        $tasksArray = array();
        foreach($tasks as $task) {
            $tasksArray[] = $task->toArray();
        }
        
        
        return $tasksArray;
    }
	
}