<?php
namespace SymfonyFirstApp\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
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
	 * @ORM\OneToOne(targetEntity="User", mappedBy="userId")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
	 */
	protected $userId;
	
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

	public function getUserId() {
		return $this->userId;
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
	
	public function setUserId(User $newUser = null) {
		$this->userId = $newUser;
	}
}