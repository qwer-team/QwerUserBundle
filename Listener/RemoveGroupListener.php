<?php

namespace Qwer\UserBundle\Listener;

use Doctrine\ORM\EntityManager;

class RemoveGroupListener
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    public function onEvent(GroupEvent $event)
    {
        $group = $event->getGroup();
        $this->em->remove($group);
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