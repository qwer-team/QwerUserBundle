<?php

namespace Qwer\UserBundle\Service;

use FOS\UserBundle\Doctrine\UserManager as FosUserManager;
use Qwer\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class UserManager extends FosUserManager
{
    /**
     * 
     * @param \Qwer\UserBundle\Entity\User $user
     * @param integer $groupId
     */
    public function addUserToGroup(User $user, $groupId)
    {
        $group = $this->getGroup($groupId);

        $user->addGroup($group);

        $this->objectManager->flush();
    }

    public function removeUserFromGroup(User $user, $groupId)
    {
        $group = $this->getGroup($groupId);

        $user->removeGroup($group);

        $this->objectManager->flush();
    }

    /**
     * 
     * @param integer $groupId
     */
    private function getGroup($groupId)
    {
        $group = $this->objectManager->getRepository("QwerUserBundle:Group")
                          ->find($groupId);

        if (!$group) {
            $message = sprintf("Group %s was not found.", $groupId);
            throw new ResourceNotFoundException($message);
        }

        return $group;
    }

}