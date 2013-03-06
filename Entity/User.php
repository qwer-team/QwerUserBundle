<?php

namespace Qwer\UserBundle\Entity;

use FOS\UserBundle\Entity\User as FOSUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 */
class User extends FOSUser
{
    /**
     * @var integer
     */
    protected $id;
    
    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection 
     */
    protected $groups;

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
