<?php

namespace Qwer\UserBundle\Core;

use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Qwer\UserBundle\Entity\Role;

class RoleHierarchy extends ContainerAware implements RoleHierarchyInterface
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    /**
     *
     * @var array 
     */
    private $map = null;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->em = $container->get("doctrine.orm.entity_manager");
    }

    public function buildRoleMap()
    {
        $rolesRepo = $this->em->getRepository("QwerUserBundle:Role");
        $roles = $rolesRepo->findAll();

        $this->map = array();

        foreach ($roles as $role) {
            $roleName = $role->getRole();

            if (isset($this->map[$roleName])) {
                continue;
            }

            $this->setReachableRoles($role);
        }
    }

    public function getReachableRoles(array $roles)
    {
        if (is_null($this->map)) {
            $this->buildRoleMap();
        }

        $reachable = array();
        foreach ($roles as $role) {
            $roleName = $role->getRole();
            if (!isset($this->map[$role->getRole()])) {
                continue;
            }

            $reachable = array_merge($reachable, $this->map[$roleName]);
        }

        return $reachable;
    }

    private function setReachableRoles(Role $role)
    {
        $parent = $role->getParent();
        $roleName = $role->getRole();
        $reachable[] = $role;

        $this->map[$roleName] = array();

        $parentRoles = array();
        if (!is_null($parent)) {
            $parentRoles = $this->setReachableRoles($parent);
        }

        $this->map[$roleName] = array_merge($parentRoles, $reachable);
        return $this->map[$roleName];
    }

}