<?php

namespace Qwer\UserBundle\Entity;

class RoleInfo extends AbstractAuthenticatedEntity
{
    /**
     *
     * @var integer 
     */
    private $id;
    
    /**
     *
     * @var string 
     */
    private $role;
    
    /**
     *
     * @var integer 
     */
    private $parentId;
    
    /**
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 
     * @param integer $id
     * @return \Qwer\UserBundle\Entity\RoleInfo
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * 
     * @param string $role
     * @return \Qwer\UserBundle\Entity\RoleInfo
     */
    public function setRole($role)
    {
        $this->role = $role;
        
        return $this;
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
     * @return \Qwer\UserBundle\Entity\RoleInfo
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
        
        return $this;
    }

}