<?php

namespace Qwer\UserBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Qwer\UserBundle\Entity\Groups;

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

    public function getGroupsAction()
    {
        $groups = new Groups();
        $groups->setGroups($this->findAll());
        $view = $this->view($groups);
        return $this->handleView($view);
    }

    public function getGroupAction($id)
    {
        $group = $this->findGroup($id);

        $view = $this->view($group);
        return $this->handleView($view);
    }

    public function postGroupsNewAction()
    {
        $info = $this->deserializeData($this->groupInfoNamespace);

        $group = $this->groupManipulator->createGroup($info);

        $view = $this->view($group);
        return $this->handleView($view);
    }

    public function postGroupsUpdateAction()
    {
        $info = $this->deserializeData($this->groupInfoNamespace);
        $id = $info->getId();

        $group = $this->findGroup($id);
        $this->groupManipulator->updateGroup($info, $group);

        $view = $this->view($group);
        return $this->handleView($view);
    }

    public function postGroupRemoveAction($id)
    {
        $type = "Qwer\UserBundle\Entity\AuthenticationInfo";
        $info = $this->deserializeData($type);

        $group = $this->findGroup($id);
        $this->groupManipulator->removeGroup($group, $info);

        $view = $this->view("success");
        return $this->handleView($view);
    }

    public function postGroupAddRoleAction($id)
    {
        $group = $this->findGroup($id);

        $roleInfo = $this->deserializeData($this->roleInfoNamespace);

        $this->roleManager->addRolesToGroup($group, $roleInfo);

        $view = $this->view($group);
        return $this->handleView($view);
    }

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