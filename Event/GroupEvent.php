<?php

namespace Qwer\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Qwer\UserBundle\Entity\Group;

class GroupEvent extends Event
{

    /**
     *
     * @var \Qwer\UserBundle\Entity\Group 
     */
    private $group;

    /**
     * 
     * @param \Qwer\UserBundle\Entity\Group $group
     */
    function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * 
     * @return \Qwer\UserBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

}