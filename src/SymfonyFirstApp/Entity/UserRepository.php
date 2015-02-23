<?php
namespace SymfonyFirstApp\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {
	
   public function findAllWithTasks()
    {
        return $this->getEntityManager()
	        ->createQuery(
	        		'SELECT u.username, COUNT(t) as tasks,
					SUM(
	        			(CASE WHEN t.status = 0
	        			then 1
	        			else 0
	        			end)
	        		) as finished
	            	FROM SymfonyFirstApp:User u
	            		LEFT JOIN u.tasks t
	            	GROUP BY u.id'
	        )
            ->getArrayResult();
    }
	
}