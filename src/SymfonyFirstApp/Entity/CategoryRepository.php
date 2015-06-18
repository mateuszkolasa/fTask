<?php
namespace SymfonyFirstApp\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {
	
   public function findArrayBy($criteria = array())
    {
        $categories = $this->findBy($criteria);
        
        $categoriesArray = array();
        foreach($categories as $category) {
            $categoriesArray[] = $category->toArray();
        }
        
        return $categoriesArray;
    }
	
}