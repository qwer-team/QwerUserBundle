<?php

namespace Qwer\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Qwer\UserBundle\Entity\Role;

class RoleEvent extends Event
{

    /**
     *
     * @var \Qwer\UserBundle\Entity\Role 
     */
    private $role;

    /**
     * 
     * @param \Qwer\UserBundle\Entity\Role $role
     */
    function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * 
     * @return \Qwer\UserBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

}