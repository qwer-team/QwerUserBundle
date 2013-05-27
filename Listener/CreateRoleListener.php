<?php

namespace Qwer\UserBundle\Listener;

use Qwer\UserBundle\Event\RoleEvent;
use Doctrine\ORM\EntityManager;

class CreateRoleListener
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    /**
     * 
     * @param \Qwer\UserBundle\Event\RoleEvent $event
     */
    public function onEvent(RoleEvent $event)
    {
        $role = $event->getRole();
        $this->em->persist($role);
        $this->em->flush();
    }

    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

}