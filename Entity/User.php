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

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection 
     */
    protected $roleObjects;

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection;
        $this->roleObjects = new ArrayCollection;
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

    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRoleObjects()
    {
        return $this->roleObjects;
    }

    /**
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $roleObjects
     * @return \Qwer\UserBundle\Entity\User
     */
    public function setRoleObjects($roleObjects)
    {
        $this->roleObjects = $roleObjects;

        return $this;
    }

    /**
     * 
     * @param \Qwer\UserBundle\Entity\Role $role
     * @return \Qwer\UserBundle\Entity\User
     */
    public function addRoleObject($role)
    {
        $this->roleObjects[] = $role;

        return $this;
    }

    /**
     * 
     * @param \Qwer\UserBundle\Entity\Role $role
     * @return \Qwer\UserBundle\Entity\User
     */
    public function removeRoleObject(Role $role)
    {
        if ($this->getRoleObjects()->contains($role)) {
            $this->getRoleObjects()->removeElement($role);
        }

        return $this;
    }

    public function addRole($role)
    {
        $this->addRoleObject($role);
        $rolename = $role->getRole();
        parent::addRole($rolename);
    }

    public function setRoles(array $roles)
    {
        $this->setRoleObjects($roles);

        $rolenames = array();
        foreach ($roles as $role) {
            $rolenames[] = $role->getRole();
        }

        parent::setRoles($rolenames);
    }

    public function removeRole($role)
    {
        if(is_string($role)) {
            return $this;
        }
        $this->removeRoleObject($role);
        $rolename = $role->getRole();
        parent::removeRole($rolename);
    }

}
