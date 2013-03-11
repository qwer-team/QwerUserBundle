<?php

namespace Qwer\UserBundle\Service;

use FOS\UserBundle\Util\UserManipulator;
use Qwer\UserBundle\Entity\RoleInfo;
use Qwer\UserBundle\Entity\User;
use Qwer\UserBundle\Entity\Group;
use Doctrine\ORM\EntityManager;

class RoleManager
{

    /**
     *
     * @var Authentication 
     */
    private $authentication;

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    public function addRolesToUser(User $user, RoleInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());

        foreach ($info->getRoles() as $role) {
            if (!$user->hasRole($role)) {
                $user->addRole($role);
            }
        }

        $this->em->flush();
    }

    public function removeRolesFromUser(User $user, RoleInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());

        foreach ($info->getRoles() as $role) {
            if ($user->hasRole($role)) {
                $user->removeRole($role);
            }
        }

        $this->em->flush();
    }

    public function addRolesToGroup(Group $group, RoleInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());

        foreach ($info->getRoles() as $role) {
            if (!$group->hasRole($role)) {
                $group->addRole($role);
            }
        }

        $this->em->flush();
    }

    public function removeRolesFromGroup(Groupe $group, RoleInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());

        foreach ($info->getRoles() as $role) {
            if ($group->hasRole($role)) {
                $group->removeRole($role);
            }
        }

        $this->em->flush();
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setAuthentication( Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

}