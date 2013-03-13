<?php

namespace Qwer\UserBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Qwer\UserBundle\Entity\Roles;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Provides methods for roles' managment in system.
 */
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

    /**
     *
     * @var string 
     */
    private $roleWasNotFound = "Role %s was not found.";

    /**
     * Role list.
     * Method url: /roles.{_format}<br />
     * Response:
     * <pre>
     * {
     *
     *    "status": "success",
     *    "data": {
     *        "roles": [
     *            {
     *                "id": 1,
     *                "name": "ROLE_USER",
     *                "parent_id": 2
     *            },
     *            {
     *                "id": 2,  
     *                "name": "ROLE_ADMIN"
     *            },
     *            {
     *                "id": 10,
     *                "name": "ROLE_CASHIER",
     *                "parent_id": 2
     *            }
     *        ]
     *    }
     *
     * }
     * </pre>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRolesAction()
    {
        $roles = new Roles();
        $roles->setRoles($this->roleManager->findAll());
        $view = $this->view($roles);
        return $this->handleView($view);
    }

    /**
     * Role info.
     * Method url: /roles/{id}.{_format} <br />
     * Response:
     * <pre>
     * {
     *
     *    "status": "success",
     *    "data": {
     *                "id": 1,
     *                "name": "ROLE_USER",
     *                "parent_id": 2
     *    }
     *
     * }
     * </pre>
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRoleAction($id)
    {
        $role = $this->roleManager->find($id);

        if (!$role) {
            $message = sprintf($this->roleWasNotFound, $id);
            throw new ResourceNotFoundException($message);
        }

        $view = $this->view($role);
        return $this->handleView($view);
    }

    /**
     * Creates new role.
     * Method url: /role/new.{_format} <br />
     * Response:
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?%gt;
     * &lt;request%gt;
     *    &lt;parent_id%gt;2&lt;/parent_id%gt;
     *    &lt;role%gt;NEW_ROLE&lt;/role%gt;
     *    &lt;authentication%gt;
     *        &lt;login%gt;rasom&lt;/login%gt;
     *        &lt;password%gt;123&lt;/password%gt;
     *    &lt;/authentication%gt;
     * &lt;/request%gt;
     * </pre>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postRoleNewAction()
    {

        $roleInfo = $this->deserializeData($this->roleInfoNamespace);
        $role = $this->roleManager->createRole($roleInfo);

        $view = $this->view($role);
        return $this->handleView($view);
    }

    /**
     * Renoves role.
     * Method url: /roles/{id}/remove.{_format} <br />
     * Request:
     * <pre>
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *      &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *      &lt;password&gt;<b>123</b>&lt;/password&gt;
     * &lt;/request&gt; 
     * </pre>
     * </pre>
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ResourceNotFoundException
     */
    public function postRoleRemoveAction($id)
    {
        $role = $this->roleManager->find($id);

        if (!$role) {
            $message = sprintf($this->roleWasNotFound, $id);
            throw new ResourceNotFoundException($message);
        }

        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        $info = $this->deserializeData($type);

        $this->roleManager->removeRole($role, $info);

        $view = $this->view("success");
        return $this->handleView($view);
    }

}