<?php

namespace STX\CroissantsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friday_Subscriptions
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="STX\CroissantsBundle\Entity\Friday_SubscriptionsRepository")
 */
class Friday_Subscriptions
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=255, nullable=true )
     */
    private $remark;

    /**
     * 
     * @var user
     * 
     * @ORM\ManyToOne(targetEntity="STX\UserBundle\Entity\User")
     */
    private $user;
    
    /**
     * 
     * @var backup_user
     * 
     * @ORM\ManyToOne(targetEntity="STX\UserBundle\Entity\User")
     */
    private $backup_user;
    
    /**
     * 
     * @var confirmation_user
     * 
     * @ORM\ManyToOne(targetEntity="STX\UserBundle\Entity\User")
     */
    private $confirmation_user;

    
    
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
     * Set date
     *
     * @param \DateTime $date
     * @return Friday_Subscriptions
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return Friday_Subscriptions
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark
     *
     * @return string 
     */
    public function getRemark()
    {
        return $this->remark;
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
	 * @return Friday_Subscriptions
	 */
	public function setUser(\STX\UserBundle\Entity\User $user) {
		$this->user = $user;
		return $this;
	}
	
	/**
	 * @return STX\UserBundle\Entity\User
	 */
	public function getBackupUser() {
		return $this->backup_user;
	}
	
	/**
	 *
	 * @param STX\UserBundle\Entity\User $backup_user
	 * @return Friday_Subscriptions
	 */
	public function setBackupUser(\STX\UserBundle\Entity\User $backup_user) {
		$this->backup_user = $backup_user;
		return $this;
	}
	
	/**
	 * @return STX\UserBundle\Entity\User
	 */
	public function getConfirmationUser() {
		return $this->confirmation_user;
	}
	
	/**
	 *
	 * @param STX\UserBundle\Entity\User $confirmation_user
	 * @return Friday_Subscriptions
	 */
	public function setConfirmationUser(\STX\UserBundle\Entity\User $confirmation_user) {
		$this->confirmation_user = $confirmation_user;
		return $this;
	}
	
	/**
	 * @return Friday_Subscriptions
	 */
	public function removeUser() {
		$this->user = NULL;
		return $this;
	}
	
	/**
	 * @return Friday_Subscriptions
	 */
	public function removeBackupUser() {
		$this->backup_user = NULL;
		return $this;
	}
	
	/**
	 * 
	 * @param Friday_Subscriptions $otherSub
	 * @return \STX\CroissantsBundle\Entity\Friday_Subscriptions
	 */
	public function updateFromSubscription(Friday_Subscriptions $otherSub) {
		
		if (is_null($otherSub->getUser())) {
			$this->removeUser();
		} else {
			$this->setUser($otherSub->getUser()) ;
		}
			
		if (is_null($otherSub->getBackupUser())) {
			$this->removeBackupUser();
		} else {
			$this->setBackupUser($otherSub->getBackupUser()) ;
		}
			
		if (is_null($otherSub->getConfirmationUser())) {
			$this->confirmation_user = NULL;
		} else {
			$this->setConfirmationUser($otherSub->getConfirmationUser()) ;
		}
			
		if (is_null($otherSub->getRemark())) {
			$this->remark = NULL;
		} else {
			$this->setRemark($otherSub->getRemark()) ;
		}
			
		
		return $this;	
	}
	
}
