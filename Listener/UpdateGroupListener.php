<?php

namespace Qwer\UserBundle\Listener;

use Qwer\UserBundle\Event\GroupEvent;
use Doctrine\ORM\EntityManager;

class UpdateGroupListener
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    public function onEvent(GroupEvent $event)
    {
        $group = $event->getGroup();
        $this->em->persist($group);
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