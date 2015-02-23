<?php
namespace SymfonyFirstApp\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Task {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	protected $title;

	/**
	 * @ORM\Column(type="integer", length=1)
	 */
	protected $priority;

	/**
	 * @ORM\Column(type="date")
	 */
	protected $date;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $isEnded;

	/**
	 * ORM\Column(type="integer")
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
	 * @ORM\JoinColumn(name="userId", referencedColumnName="id")
	 */
	protected $userId;

	/**
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="tasks")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     * 
	 */
	protected $category;
	
	public function getId() {
		return $this->id;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getPriority() {
		return $this->priority;
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function getIsEnded() {
		return $this->isEnded;
	}
	
	public function getCategory() {
		return $this->category;
	}
	
	public function getUserId() {
		return $this->userId;
	}
	
	public function setTitle($newTitle) {
		$this->title = $newTitle;
	}
	
	public function setPriority($newPriority) {
		$this->priority = $newPriority;
	}
	
	public function setDate($newDate) {
		$this->date = $newDate;
	}
	
	public function setUserId(User $newUser = null) {
		$this->userId = $newUser;
	}
	
	public function setIsEnded($isEnded) {
		if($isEnded == "1") $this->isEnded = true;
		else $this->isEnded = false;
	}
	
	public function setCategory(Category $newCategory = null) {
		$this->category = $newCategory;
	}
}