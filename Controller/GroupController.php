<?php

namespace Qwer\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class GroupController extends RestController
{

    public $groupManipulator;
    public function getGroupInfoAction($id)
    {
        $group = $this->getGroupRepository()->find($id);
        $view = $this->view($group);

        if (!$group) {
            $message = sprintf("User %s was not found", $id);
            throw new ResourceNotFoundException($message);
        }

        return $this->handleView($view);
    }

    public function postGroupsNewAction()
    {
        $type = "Qwer\UserBundle\GroupInfo";
        $info = $this->deserializeData("data", $type);
        
        
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

}