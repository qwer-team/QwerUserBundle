<?php

namespace Qwer\UserBundle\Listener;

use Doctrine\ORM\EntityManager;
use Qwer\UserBundle\Event\RoleEvent;
use Qwer\UserBundle\Entity\Role;

class RemoveRoleListener
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    /**
     * 
     * @param \Qwer\UserBundle\Listener\RoleEvent $event
     */
    public function onEvent(RoleEvent $event)
    {
        $role = $event->getRole();
        $this->removeFromGroups($role);
        $this->removeFromUsers($role);
        $this->em->remove($role);
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

    private function removeFromGroups(Role $role)
    {
        $groups = $role->getGroups();
        
        foreach($groups as $group){
            $group->removeRole($role);
        }
    }

    private function removeFromUsers(Role $role)
    {
        $users = $role->getUsers();
        
        foreach($users as $user){
            $user->removeRole($role);
        }
    }

}