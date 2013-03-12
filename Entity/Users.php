<?php

namespace Qwer\UserBundle\Entity;

/**
 * Description of Users
 *
 * @author root
 */
class Users
{

    private $users;

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }

}