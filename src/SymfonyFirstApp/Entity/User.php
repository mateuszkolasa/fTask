<?php
namespace SymfonyFirstApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @UniqueEntity("username")
 */
class User implements UserInterface, \Serializable {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=50, unique=true)
	 * 
	 */
	protected $username;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	protected $password;

    /**
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    public function __construct() {
    	$this->isActive = true;
    }
    
	public function getId() {
		return $this->id;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getRoles() {
		return array('ROLE_USER'); 
	}
	
	public function getSalt() { return null; }
	
	public function eraseCredentials() {
		
	}
	
	public function serialize() {
		return serialize(array(
				$this->id,
				$this->username,
				$this->password,
				// see section on salt below
				// $this->salt,
		));
	}

	public function unserialize($serialized) {
		list (
				$this->id,
				$this->username,
				$this->password,
				// see section on salt below
				// $this->salt
		) = unserialize($serialized);
	}

	public function setUsername($newUsername) {
		$this->username = $newUsername;
	}
	
	public function setPassword($newPassword) {
		$this->password = $newPassword;
	}
}