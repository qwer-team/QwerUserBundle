<?php

namespace Qwer\UserBundle\Service;

use FOS\UserBundle\Util\UserManipulator;
use Qwer\UserBundle\Entity\RoleInfo;
use Qwer\UserBundle\Entity\User;
use Qwer\UserBundle\Entity\Group;
use Qwer\UserBundle\Entity\RolesInfo;
use Qwer\UserBundle\Entity\Role;
use Doctrine\ORM\EntityManager;
use Qwer\UserBundle\Event\RoleEvent;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Qwer\UserBundle\Entity\AuthenticationInfo;


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

    /**
     *
     * @var \Doctrine\ORM\EntityRepository 
     */
    private $roleRepo;
    
    /**
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface 
     */
    private $dispatcher;

    public function createRole(RoleInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());
        
        $role = new Role();
        $parentId = $info->getParentId();
        $rolename = $info->getRole();
        $parentRole = $this->find($parentId);
        
        $role->setParent($parentRole);
        $role->setName($rolename);
        
        $event = new RoleEvent($role);
        $this->dispatcher->dispatch("create.role.event", $event);
        
        return $role;
    }

    public function removeRole(Role $role, AuthenticationInfo $info)
    {
        $this->authentication->authenticate($info);
        
        $event = new RoleEvent($role);
        $this->dispatcher->dispatch("remove.role.event", $event);
    }

    public function addRolesToUser(User $user, RolesInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());

        $roles = $this->getRoles($info->getRoles());
        foreach ($roles as $role) {
            if (!$user->hasRole($role->getRole())) {
                $user->addRole($role);
            }
        }

        $this->em->flush();
    }

    public function removeRolesFromUser(User $user, RolesInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());

        $roles = $this->getRoles($info->getRoles());
        foreach ($roles as $role) {
            if ($user->hasRole($role->getRole())) {
                $user->removeRole($role);
            }
        }

        $this->em->flush();
    }

    public function addRolesToGroup(Group $group, RolesInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());

        $roles = $this->getRoles($info->getRoles());
        foreach ($roles as $role) {
            if (!$group->hasRole($role->getRole())) {
                $group->addRole($role);
            }
        }

        $this->em->flush();
    }

    public function removeRolesFromGroup(Group $group, RolesInfo $info)
    {
        $this->authentication->authenticate($info->getAuthenticationInfo());

        $roles = $this->getRoles($info->getRoles());
        foreach ($roles as $role) {
            if ($group->hasRole($role->getRole())) {
                $group->removeRole($role);
            }
        }

        $this->em->flush();
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
        $this->roleRepo = $this->em->getRepository("QwerUserBundle:Role");
    }

    public function setAuthentication(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }
    
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    private function getRoles(array $rolesIds)
    {
        return $this->roleRepo->findAllByIds($rolesIds);
    }

    public function findAll()
    {
        return $this->roleRepo->findAll();
    }

    public function find($id)
    {
        return $this->roleRepo->find($id);
    }

}