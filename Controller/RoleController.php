<?php

namespace Qwer\UserBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Qwer\UserBundle\Entity\Roles;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RoleController extends RestController
{

    /**
     * @DI\Inject("role_manager")
     * @var \Qwer\UserBundle\Service\RoleManager
     */
    public $roleManager;

    /**
     *
     * @var string 
     */
    private $roleInfoNamespace = "Qwer\UserBundle\Entity\RoleInfo";

    public function getRolesAction()
    {
        $roles = new Roles();
        $roles->setRoles($this->roleManager->findAll());
        $view = $this->view($roles);
        return $this->handleView($view);
    }

    public function getRoleAction($id)
    {
        $role = $this->roleManager->find($id);
        $view = $this->view($role);
        return $this->handleView($view);
    }

    public function postRoleNewAction()
    {

        $roleInfo = $this->deserializeData($this->roleInfoNamespace);
        $role = $this->roleManager->createRole($roleInfo);

        $view = $this->view($role);
        return $this->handleView($view);
    }

    public function postRoleRemoveAction($id)
    {
        $role = $this->roleManager->find($id);

        if (!$role) {
            $message = sprintf("Role %s was not found.", $id);
            throw new ResourceNotFoundException($message);
        }
        
        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        $info = $this->deserializeData($type);
        
        $this->roleManager->removeRole($role, $info);
        
        $view = $this->view("success");
        return $this->handleView($view);
    }

}