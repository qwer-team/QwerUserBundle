<?php

namespace Qwer\UserBundle\Entity;

use Qwer\UserBundle\Entity\AbstractAuthenticatedEntity;

class RoleInfo extends AbstractAuthenticatedEntity
{

    private $roles = array();

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function addRole($role)
    {
        $this->roles[] = $role;
    }

}