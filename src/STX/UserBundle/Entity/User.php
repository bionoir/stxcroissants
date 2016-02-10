<?php

namespace STX\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="croissants_user")
 */
class User extends BaseUser
{

	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	
	/**
	 * @var string
	 * 
	 * @ORM\Column(name="firstname", type="string", length=80, nullable=true)
	 */
	private $firstname;
	
	
	/**
	 * @var string
	 * 
	 * @ORM\Column(name="lastname", type="string", length=80, nullable=true)
	 */
	private $lastname;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="days", type="integer", options={"default" = 10})
	 * @Assert\GreaterThan(value = 0)
	 * @Assert\LessThan(value = 11)
	 */
	private $days;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="alert_email", type="boolean", options={"default" = 1})
	 */
	private $alertEmail;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="alert_days", type="integer", options={"default" = 1})
	 * @Assert\GreaterThan(value = 0)
	 * @Assert\LessThan(value = 7)
	 */
	private $alertDays;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="email_visible", type="boolean", options={"default" = 0})
	 */
	private $emailVisible;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="homepage", type="string", length=255)
	 */
	private $homepage;
	
	public function __construct()
	{
		parent::__construct();
		$this->days = 10;
		$this->alertEmail = false;
		$this->alertDays = 1;
		$this->emailVisible = false;
		$this->homepage = 'Aucune';
		
	}
	
	/**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used by the equals method and the username.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
        	$this->days,
        		$this->alertEmail,
        		$this->alertDays,
        		$this->emailVisible,
        		$this->homepage,
        	$this->firstname,
        	$this->lastname,
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->expired,
            $this->locked,
            $this->credentialsExpired,
            $this->enabled,
            $this->id,
        ));
    }
    
    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
    	$data = unserialize($serialized);
    	// add a few extra elements in the array to ensure that we have enough keys when unserializing
    	// older data which does not include all properties.
    	$data = array_merge($data, array_fill(0, 2, null));
    
    	list(
    			$this->days,
    			$this->alertEmail,
    			$this->alertDays,
    			$this->emailVisible,
    			$this->homepage,
    			$this->firstname,
    			$this->lastname,
    			$this->password,
    			$this->salt,
    			$this->usernameCanonical,
    			$this->username,
    			$this->expired,
    			$this->locked,
    			$this->credentialsExpired,
    			$this->enabled,
    			$this->id
    			) = $data;
    }
    
    
    /**
     * Gets the user firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
    	return $this->firstname;
    }
    
    
    /**
     * Gets the user lastname.
     *
     * @return string
     */
    public function getLastname()
    {
    	return $this->lastname;
    }
    
    
    /**
     * @param string $newName
     *
     * @return User
     */
    public function setFirstname($newName)
    {
    	$this->firstname = $newName;
    	 
    	return $this;
    }
    
    /**
     * @param string $newName
     *
     * @return User
     */
    public function setLastname($newName)
    {
    	$this->lastname = $newName;
    	
    	return $this;
    }
    
    /**
     * Set days
     *
     * @param integer $days
     * @return Preferences
     */
    public function setDays($days)
    {
    	$this->days = $days;
    
    	return $this;
    }
    
    /**
     * Get days
     *
     * @return integer
     */
    public function getDays()
    {
    	return $this->days;
    }
    
    /**
     * Set alertEmail
     *
     * @param boolean $alertEmail
     * @return Preferences
     */
    public function setAlertEmail($alertEmail)
    {
    	$this->alertEmail = $alertEmail;
    
    	return $this;
    }
    
    /**
     * Get alertEmail
     *
     * @return boolean
     */
    public function getAlertEmail()
    {
    	return $this->alertEmail;
    }
    
    /**
     * Set alertDays
     *
     * @param integer $alertDays
     * @return Preferences
     */
    public function setAlertDays($alertDays)
    {
    	$this->alertDays = $alertDays;
    
    	return $this;
    }
    
    /**
     * Get alertDays
     *
     * @return integer
     */
    public function getAlertDays()
    {
    	return $this->alertDays;
    }
    
    /**
     * Set emailVisible
     *
     * @param boolean $emailVisible
     * @return Preferences
     */
    public function setEmailVisible($emailVisible)
    {
    	$this->emailVisible = $emailVisible;
    
    	return $this;
    }
    
    /**
     * Get emailVisible
     *
     * @return boolean
     */
    public function getEmailVisible()
    {
    	return $this->emailVisible;
    }
    
    /**
     * Set homepage
     *
     * @param string $homepage
     * @return Preferences
     */
    public function setHomepage($homepage)
    {
    	$this->homepage = $homepage;
    
    	return $this;
    }
    
    /**
     * Get homepage
     *
     * @return string
     */
    public function getHomepage()
    {
    	return $this->homepage;
    }
    
}