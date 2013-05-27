<?php

namespace Qwer\UserBundle\Entity;


class Roles
{
    /**
     *
     * @var array 
     */
    private $roles = array();
    
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

}