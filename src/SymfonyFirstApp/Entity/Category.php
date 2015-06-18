<?php
namespace SymfonyFirstApp\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="SymfonyFirstApp\Entity\CategoryRepository")
 */
class Category {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="integer", length=1)
	 */
	protected $color;

	/**
	 * @ORM\OneToOne(targetEntity="User", mappedBy="user")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
	 */
	protected $user;
	
	/**
	 * @ORM\OneToMany(targetEntity="Task", mappedBy="category")
	 *
	 */
	protected $tasks;

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getColor() {
		return $this->color;
	}

	public function getUser() {
		return $this->user;
	}
	
	public function getTasks() {
		return $this->tasks;
	}

	public function setName($newName) {
		$this->name = $newName;
	}

	public function setColor($newColor) {
		$this->color = (int) $newColor;
	}
	
	public function setUser(User $newUser = null) {
		$this->user = $newUser;
	}
	
	public function getClass() {
		if($this->color == 2) return 'primary';
		if($this->color == 3) return 'success';
		if($this->color == 4) return 'info';
		if($this->color == 5) return 'warning';
		if($this->color == 6) return 'danger';
		
		return 'default';
	}
	
	public function toArray() {
	    return array(
	        'id' => $this->id,
	        'name' => $this->name,
	        'color' => $this->color,
	        'class' => $this->getClass()
	    );
	}
}