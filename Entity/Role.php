<?php

namespace Qwer\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Role
 */
class Role implements RoleInterface
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Role
     */
    private $parent;

    /**
     *
     * @var integer 
     */
    private $parentId;
    
    private $users;
    
    private $groups;
    
    function __construct()
    {
        $this->users = new ArrayCollection();
        $this->groups = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parent
     *
     * @param Role $parent
     * @return Role
     */
    public function setParent(Role $parent)
    {
        $this->parent = $parent;
        $this->parentId = $parent->getId();

        return $this;
    }

    /**
     * Get parent
     *
     * @return string 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get role
     * 
     * @return string
     */
    public function getRole()
    {
        return $this->getName();
    }

    /**
     * 
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * 
     * @param integer $parentId
     * @return \Qwer\UserBundle\Entity\Role
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

}
