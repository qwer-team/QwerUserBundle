<?php

namespace Qwer\UserBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Qwer\UserBundle\Entity\Groups;

/**
 * Provides methods for groups' managment in system.
 */
class GroupController extends RestController
{

    /**
     * @DI\Inject("group_manipulator")
     * @var \Qwer\UserBundle\Service\GroupManipulator
     */
    public $groupManipulator;

    /**
     * @DI\Inject("role_manager")
     * @var \Qwer\UserBundle\Service\RoleManager
     */
    public $roleManager;

    /**
     *
     * @var string 
     */
    private $groupWasNotFoundMessage = "Group %s was not found";

    /**
     *
     * @var string 
     */
    private $groupInfoNamespace = "Qwer\UserBundle\Entity\GroupInfo";

    /**
     *
     * @var string 
     */
    private $roleInfoNamespace = "Qwer\UserBundle\Entity\RolesInfo";

    /**
     * Groups List.
     * Method url: /groups.{_format}<br />
     * Response example: <br />
     * 1) json 
     * <pre>
     * {
     *
     *    "status": "success",
     *    "data": {
     *        "groups": [
     *            {
     *                "id": 1,
     *                "name": "admin",
     *                "roles": [
     *                    "ROLE_ADMIN"
     *                ],
     *                "description": "admins"
     *            },
     *            {
     *                "id": 4,
     *                "name": "cassiers",
     *                "roles": [
     *                    "ROLE_CASHIER"
     *                ],
     *               "description": "new group description"
     *            },
     *            ...
     *        ]
     *    }
     *
     * }
     * </pre>
     * 2) xml
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     *   &lt;result&gt;
     *     &lt;status&gt;&lt;![CDATA[success]]&gt;&lt;/status&gt;
     *     &lt;data&gt;
     *       &lt;groups&gt;
     *         &lt;group&gt;
     *           &lt;id&gt;1&lt;/id&gt;
     *           &lt;name&gt;&lt;![CDATA[admin]]&gt;&lt;/name&gt;
     *           &lt;roles&gt;
     *             &lt;role&gt;&lt;![CDATA[ROLE_ADMIN]]&gt;&lt;/role&gt;
     *           &lt;/roles&gt;
     *           &lt;description&gt;&lt;![CDATA[]]&gt;&lt;/description&gt;
     *         &lt;/group&gt;
     *         &lt;group&gt;
     *           &lt;id&gt;4&lt;/id&gt;
     *           &lt;name&gt;&lt;![CDATA[cassiers]]&gt;&lt;/name&gt;
     *           &lt;roles&gt;
     *             &lt;role&gt;&lt;![CDATA[ROLE_CASHIER]]&gt;&lt;/role&gt;
     *           &lt;/roles&gt;
     *           &lt;description&gt;&lt;![CDATA[new group description]]&gt;&lt;/description&gt;
     *         &lt;/group&gt;
     *       &lt;/groups&gt;
     *     &lt;/data&gt;
     *   &lt;/result&gt;
     * </pre>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getGroupsAction()
    {
        $groups = new Groups();
        $groups->setGroups($this->findAll());
        $view = $this->view($groups);
        return $this->handleView($view);
    }

    /**
     * Infornation about group.
     * Method url: /groups/{id}.{_format} <br />
     * Response example:
     * <pre>
     * {
     *
     *    "status": "success",
     *    "data": {
     *        "id": 4,
     *        "name": "cassiers",
     *        "roles": [
     *            "ROLE_CASHIER"
     *        ],
     *        "description": "description info"
     *    }
     *
     * }
     * </pre>
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getGroupAction($id)
    {
        $group = $this->findGroup($id);

        $view = $this->view($group);
        return $this->handleView($view);
    }

    /**
     * Creates new group.
     * Method url: /groups/new.{_format} <br />
     * Request examle:
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *    &lt;id&gt;2&lt;/id&gt;
     *    &lt;name&gt;new group 234234&lt;/name&gt;
     *    &lt;description&gt;new group description&lt;/description&gt;
     *    &lt;authentication&gt;
     *        &lt;login&gt;rasom&lt;/login&gt;
     *        &lt;password&gt;123&lt;/password&gt;
     *    &lt;/authentication&gt;
     * &lt;/request&gt;
     * </pre>
     * @return \Symfony\Component\HttpFoundation\Respons
     */
    public function postGroupsNewAction()
    {
        $info = $this->deserializeData($this->groupInfoNamespace);

        $group = $this->groupManipulator->createGroup($info);

        $view = $this->view($group);
        return $this->handleView($view);
    }

    /**
     * Updates group info.
     * Method url: \Symfony\Component\HttpFoundation\Respons <br />
     * Request examle:
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *    &lt;id&gt;2&lt;/id&gt;
     *    &lt;name&gt;new group 234234&lt;/name&gt;
     *    &lt;description&gt;new group description&lt;/description&gt;
     *    &lt;authentication&gt;
     *        &lt;login&gt;rasom&lt;/login&gt;
     *        &lt;password&gt;123&lt;/password&gt;
     *    &lt;/authentication&gt;
     * &lt;/request&gt;
     * </pre> 
     * @return \Symfony\Component\HttpFoundation\Respons
     */
    public function postGroupsUpdateAction()
    {
        $info = $this->deserializeData($this->groupInfoNamespace);
        $id = $info->getId();

        $group = $this->findGroup($id);
        $this->groupManipulator->updateGroup($info, $group);

        $view = $this->view($group);
        return $this->handleView($view);
    }

    /**
     * Removes group.
     * Method url: \Symfony\Component\HttpFoundation\Respons
     * Request example:
     * <pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *      &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *      &lt;password&gt;<b>123</b>&lt;/password&gt;
     * &lt;/request&gt; 
     * </pre>
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Respons
     */
    public function postGroupRemoveAction($id)
    {
        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        $info = $this->deserializeData($type);

        $group = $this->findGroup($id);
        $this->groupManipulator->removeGroup($group, $info);

        $view = $this->view("success");
        return $this->handleView($view);
    }

    /**
     * Adds roles to group.
     * Method url: /groups/{id}/add/role.{_format}
     * Запрос:<pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *    &lt;roles&gt;
     *        &lt;role&gt;<b>10&lt;!--roleId--&gt;</b>&lt;/role&gt;
     *        &lt;role&gt;<b>15&lt;!--roleId--&gt;</b>&lt;/role&gt;
     *        ...
     *    &lt;/roles&gt;
     *  &lt;authentication&gt;
     *      &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *      &lt;password&gt;<b>123</b>&lt;/password&gt;
     *  &lt;/authentication&gt;
     * &lt;/request&gt;
     * </pre>
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postGroupAddRoleAction($id)
    {
        $group = $this->findGroup($id);

        $roleInfo = $this->deserializeData($this->roleInfoNamespace);

        $this->roleManager->addRolesToGroup($group, $roleInfo);

        $view = $this->view($group);
        return $this->handleView($view);
    }

    /**
     * Removes roles to group.
     * Method url: /groups/{id}/remove/role.{_format}
     * Запрос:<pre>
     * &lt;?xml version="1.0" encoding="UTF-8"?&gt;
     * &lt;request&gt;
     *    &lt;roles&gt;
     *        &lt;role&gt;<b>10&lt;!--roleId--&gt;</b>&lt;/role&gt;
     *        &lt;role&gt;<b>15&lt;!--roleId--&gt;</b>&lt;/role&gt;
     *        ...
     *    &lt;/roles&gt;
     *  &lt;authentication&gt;
     *      &lt;login&gt;<b>rasom</b>&lt;/login&gt;
     *      &lt;password&gt;<b>123</b>&lt;/password&gt;
     *  &lt;/authentication&gt;
     * &lt;/request&gt;
     * </pre>
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postGroupRemoveRoleAction($id)
    {
        $group = $this->findGroup($id);

        $roleInfo = $this->deserializeData($this->roleInfoNamespace);

        $this->roleManager->removeRolesFromGroup($group, $roleInfo);

        $view = $this->view($group);
        return $this->handleView($view);
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getGroupRepository()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");
        $repo = $em->getRepository("QwerUserBundle:Group");

        return $repo;
    }

    private function findGroup($id)
    {
        $group = $this->getGroupRepository()->find($id);
        if (!$group) {
            $message = sprintf($this->groupWasNotFoundMessage, $id);
            throw new ResourceNotFoundException($message);
        }

        return $group;
    }

    private function findAll()
    {
        return $this->getGroupRepository()->findAll();
    }

}