<?php
namespace SymfonyFirstApp\Entity;

use Doctrine\Common\NotifyPropertyChanged;
use Doctrine\Common\PropertyChangedListener;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * 
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 * 
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="SymfonyFirstApp\Entity\TaskRepository")
 */
class Task {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @Gedmo\Versioned
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
	protected $status;
	
	/**
	 * @var datetime $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
	 */
	private $updated;
	
	/**
	 * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
	 */
	private $deletedAt;
	
	/**
	 * 
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
	 * @ORM\JoinColumn(name="user", referencedColumnName="id")
	 */
	protected $user;

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
	
	public function getPriorityName() {
		if($this->status == false) return 'success';
		
		if($this->priority == 2) return 'warning';
		if($this->priority == 3) return 'danger';
		
		return 'info';
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function getUpdated() {
		return $this->updated;
	}
	
	public function getCategory() {
		return $this->category;
	}
	
	public function getCategoryClass() {
		if($this->category->getColor() == 2) return 'primary';
		if($this->category->getColor() == 3) return 'success';
		if($this->category->getColor() == 4) return 'info';
		if($this->category->getColor() == 5) return 'warning';
		if($this->category->getColor() == 6) return 'danger';
		
		return 'default';
	}
	
	public function getUser() {
		return $this->user;
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
	
	public function setUser(User $newUser = null) {
		$this->user = $newUser;
	}
	
	public function setStatus($isOpen = true) {
		$this->status = (bool) $isOpen;
	}
	
	public function changeStatus() {
	    $this->status = !$this->status;
	}
	
	/*public function setUpdated($newDateTime) {
		$this->updated = $newDateTime;
	}*/
	
	public function setCategory(Category $newCategory = null) {
		$this->category = $newCategory;
	}
	
	public function toArray() {
	    $category = null;
	    if($this->category != null) {
	        $category = $this->category->toArray();
	        $category['className'] = $this->getCategoryClass();
	    }
	    
	    return array(
	        'id' => $this->id,
	        'category' => $category,
	        'title' => $this->title,
	        'status' => $this->status,
	        'priority' => $this->getPriorityName()
	    );
	}
}