<?php

namespace STX\CroissantsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Preferences
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="STX\CroissantsBundle\Entity\PreferencesRepository")
 */
class Preferences
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
	 * @var integer
	 *
	 * @ORM\Column(name="days", type="integer", options={"default" = 10})
	 */
	private $days;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="alert_email", type="boolean", options={"default" = 0})
	 */
	private $alertEmail;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="alert_days", type="integer", options={"default" = 0})
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
	
	/**
	 *
	 * @var user
	 *
	 * @ORM\OneToOne(targetEntity="STX\UserBundle\Entity\User")
	 */
	private $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
    
    /**
     * @return STX\UserBundle\Entity\User
     */
    public function getUser() {
    	return $this->user;
    }
    
    /**
     *
     * @param STX\UserBundle\Entity\User $user
     * @return Preferences
     */
    public function setUser(\STX\UserBundle\Entity\User $user) {
    	$this->user = $user;
    	return $this;
    }
}
