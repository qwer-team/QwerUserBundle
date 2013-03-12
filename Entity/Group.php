<?php

namespace Qwer\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\Group as FOSGroup;

/**
 * Groupp
 */
class Group extends FOSGroup
{

    /**
     *
     * @var string 
     */
    private $description;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection 
     */
    protected $roleObjects;

    /**
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * 
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
        $this->removeRoleObject($role);
        $rolename = $role->getRole();
        parent::removeRole($rolename);
    }
}
