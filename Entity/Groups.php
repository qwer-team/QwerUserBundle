<?php

namespace Qwer\UserBundle\Entity;

/**
 * Description of Groups
 *
 * @author root
 */
class Groups
{

    private $groups;

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

}